<?php

namespace App\Imports;

use App\Models\MatrixTraining;
use App\Models\Training;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;

// Gunakan nama heading persis di Excel
HeadingRowFormatter::default('none');

class MasterTrainingImport implements OnEachRow
{
    public function onRow(Row $row)
    {
        $rowIndex = $row->getIndex();

        if ($rowIndex === 1) {
            return;
        }

        $row = $row->toArray();

        $headings = ['kode', 'program_training', 'golongan', 'department', 'purpose', 'duration'];

        $row = array_slice($row, 0, count($headings));
        $row = array_pad($row, count($headings), '');
        $row = array_combine($headings, $row);

        $code = trim($row['kode'] ?? '');
        $name = trim($row['program_training'] ?? '');
        $golongan = trim($row['golongan'] ?? '');
        $purpose = trim($row['purpose'] ?? '');
        $duration = trim($row['duration'] ?? '');
        $departmentsRaw = $row['department'] ?? '';

        $departments = array_map('trim', explode(',', $departmentsRaw));
        if (empty($departments)) {
            $departments = [null];
        }

        if ($code === '') {
            echo "Skipping row {$rowIndex}: empty code" . PHP_EOL;
            return;
        }

        echo "Processing row {$rowIndex}: code={$code}, departments=" . implode(',', array_map(fn($d) => $d ?? 'NULL', $departments)) . PHP_EOL;

        try {
            $training = Training::updateOrCreate(
                ['code' => $code],
                [
                    'name' => $name,
                    'golongan' => $golongan,
                    'purpose' => $purpose,
                    'duration' => $duration
                ]
            );

            $matrixData = [];
            foreach ($departments as $dept) {
                $matrixData[] = [
                    'training_id' => $training->code,
                    'dept' => $dept,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }

            MatrixTraining::upsert(
                $matrixData,
                ['training_id', 'dept'],
                ['updated_at']
            );

            echo "  -> matrix_training upserted" . PHP_EOL;
        } catch (\Exception $e) {
            Log::error("Training import error at row {$rowIndex}: " . $e->getMessage(), ['row' => $row]);
            echo "  !! Error at row {$rowIndex}, see log" . PHP_EOL;
        }
    }
}
