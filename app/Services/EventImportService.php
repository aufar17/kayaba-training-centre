<?php

namespace App\Services;

use App\Interfaces\ServiceInterface\EventImportServiceInterface;
use App\Models\Event;
use App\Models\EventTransaction;
use App\Models\Organizer;
use App\Models\Training;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class EventImportService implements EventImportServiceInterface
{
    public function import(array $rows): void
    {
        DB::transaction(function () use ($rows) {

            $unique = collect($rows)
                ->unique(
                    fn($row) =>
                    strtolower(trim($row['training id'] ?? '')) . '|' .
                        strtolower(trim($row['organizer'] ?? ''))
                )
                ->values()
                ->all();

            $this->importOrganizers($unique);

            $events = $this->importEvents($unique);

            $this->importParticipants($rows, $events);
        });
    }

    private function generateOrganizerCode(): string
    {
        $last = Organizer::orderBy('id', 'DESC')->first();

        $next = $last && preg_match('/ORG(\d+)/', $last->code, $matches)
            ? intval($matches[1]) + 1
            : 1;

        return 'ORG' . str_pad($next, 3, '0', STR_PAD_LEFT);
    }

    private int $eventCounter = 0;

    private function generateEventCode(): string
    {
        if ($this->eventCounter === 0) {
            $last = Event::orderBy('id', 'desc')->first();
            $start = $last ? ((int) str_replace('EVT', '', $last->code)) + 1 : 1;
            $this->eventCounter = $start;
        }

        // increment untuk berikutnya
        $code = 'EVT' . str_pad($this->eventCounter, 4, '0', STR_PAD_LEFT);
        $this->eventCounter++;

        return $code;
    }

    private function parseExcelDate($value): ?string
    {
        if (!$value) return null;

        try {
            if (is_numeric($value)) {
                $date = Date::excelToDateTimeObject($value);
            } else {
                $formats = ['d M y', 'd/m/y', 'd-m-y', 'Y-m-d'];
                $date = null;
                foreach ($formats as $format) {
                    try {
                        $date = Carbon::createFromFormat($format, trim($value));
                        break;
                    } catch (\Exception $e) {
                        continue;
                    }
                }
                if (!$date) return null;
            }

            return $date->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }

    private function parseExcelTime(?string $value): ?string
    {
        if (!$value) return null;

        $value = trim($value);

        if (is_numeric($value)) {
            return Date::excelToDateTimeObject($value)
                ->format('H:i:s');
        }

        $formats = ['H:i', 'H:i:s'];

        foreach ($formats as $format) {
            try {
                return Carbon::createFromFormat($format, $value)->format('H:i:s');
            } catch (\Exception $e) {
                continue;
            }
        }

        return null;
    }


    private function importOrganizers(array $rows): void
    {
        $cache = [];

        foreach ($rows as $row) {
            if (empty($row['organizer'])) continue;

            $original = trim($row['organizer']);
            $normalized = strtolower(preg_replace('/\s+/', '', $original));

            if (isset($cache[$normalized])) continue;

            $exists = Organizer::whereRaw(
                "LOWER(REPLACE(name, ' ', '')) = ?",
                [$normalized]
            )->first();

            if (!$exists) {
                Organizer::create([
                    'code' => $this->generateOrganizerCode(),
                    'name' => $original,
                ]);
            }

            $cache[$normalized] = true;
        }
    }


    private function importEvents(array $rows): array
    {
        $events = [];

        foreach ($rows as $row) {
            if (empty($row['training id'])) continue;

            $startDate = $this->parseExcelDate($row['start date']);
            $endDate   = $this->parseExcelDate($row['end date']);
            $startTime = $this->parseExcelTime($row['start hour']);
            $endTime   = $this->parseExcelTime($row['end hour']);

            $key = strtolower(
                trim($row['training id']) . '|' .
                    trim($row['organizer'] ?? '') . '|' .
                    ($startDate ?? 'nodate')
            );

            if (isset($events[$key])) {
                $event = $events[$key];

                if ($startTime && (!$event->start_time || $startTime < $event->start_time)) {
                    $event->start_time = $startTime;
                }

                if ($endTime && (!$event->end_time || $endTime > $event->end_time)) {
                    $event->end_time = $endTime;
                }

                $event->save();
                continue;
            }

            $organizerCode = null;
            if (!empty($row['organizer'])) {
                $organizer = Organizer::whereRaw('LOWER(TRIM(name)) = ?', [strtolower(trim($row['organizer']))])->first();
                $organizerCode = $organizer->code ?? null;
            }

            $training = Training::firstOrCreate(
                ['code' => $row['training id']],
                ['name' => $row['training'] ?? 'N/A']
            );

            $event = Event::firstOrNew([
                'training_id'  => $training->code,
                'location_id'  => 'LOC0001',
                'organizer_id' => $organizerCode,
                'trainer_id'   => 'TA0001',
                'start_date'   => $startDate,
                'end_date'     => $endDate,
            ]);

            if (!$event->exists) {
                $event->code = $this->generateEventCode();
            }

            // time safe update
            if ($startTime) $event->start_time = $startTime;
            if ($endTime)   $event->end_time   = $endTime;

            $event->save();
            $events[$key] = $event;
        }

        return $events;
    }

    private function importParticipants(array $rows, array $events): void
    {
        foreach ($rows as $row) {

            if (empty($row['npk']) || empty($row['training id'])) {
                continue;
            }

            $startDate = $this->parseExcelDate($row['start date'] ?? null);

            $key = strtolower(
                trim($row['training id'] ?? '') . '|' .
                    trim($row['organizer'] ?? '') . '|' .
                    ($startDate ?? 'nodate')
            );

            $event = $events[$key] ?? null;
            if (!$event) continue;

            EventTransaction::firstOrCreate(
                [
                    'event_id' => $event->code,
                    'npk'      => $row['npk'],
                ],
                [
                    'approval'   => 2,
                    'completed'  => 1,
                ]
            );
        }
    }
}
