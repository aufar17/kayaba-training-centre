<?php

namespace App\Exports;

use App\Interfaces\RepositoryInterface\EventRepositoryInterface;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Worksheet\PageMargins;

class PresenceExport implements WithStyles, WithHeadings, WithCustomStartCell
{
    protected $event;
    protected $participants;
    protected $trainers;
    protected $organizers;

    public function __construct(EventRepositoryInterface $eventRepo, int $id)
    {
        $this->event = $eventRepo->find($id);

        $this->participants = $this->event->transactions;
        $this->trainers = $this->event->trainers;
        $this->organizers = $this->event->organizers;
    }

    public function startCell(): string
    {
        return 'B7';
    }

    public function headings(): array
    {
        return [
            'NO',
            'NPK',
            'NAMA',
            'COMPANY/DEPT',
            'TANDA TANGAN',
        ];
    }

    private function addLogo(Worksheet $sheet): void
    {
        $logo = new Drawing();
        $logo->setName('Company Logo');
        $logo->setDescription('Company Logo');
        $logo->setPath(public_path('img/logo.png'));
        $logo->setHeight(50);

        $logo->setCoordinates('C3');

        $logo->setOffsetX(50);
        $logo->setOffsetY(-25);

        $logo->setWorksheet($sheet);
    }


    public function styles(Worksheet $sheet)
    {

        $sheet->getPageSetup()
            ->setPaperSize(PageSetup::PAPERSIZE_A4)
            ->setOrientation(PageSetup::ORIENTATION_LANDSCAPE)
            ->setFitToWidth(1)
            ->setFitToHeight(1);
        $sheet->getParent()->getDefaultStyle()->applyFromArray([
            'font' => [
                'name' => 'Arial',
            ],
        ]);
        $sheet->getStyle('B2:G37')->applyFromArray([
            'borders' => [
                'outline' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ]);

        $sheet->mergeCells('B2:D3');
        $this->addLogo($sheet);

        $sheet->getStyle('B2:D3')->applyFromArray([
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'outline' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ]);


        $sheet->mergeCells('E2:G3');
        $sheet->setCellValue('E2', 'ABSENSI TRAINING');

        $sheet->getStyle('E2:G3')->applyFromArray([
            'font' => ['bold' => true, 'size' => 20],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'outline' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ]);

        $sheet->mergeCells('B4:C4');
        $sheet->mergeCells('B5:C5');
        $sheet->mergeCells('F4:G4');
        $sheet->mergeCells('F5:G5');

        $sheet->setCellValue('B4', 'AGENDA :');
        $sheet->setCellValue('D4', $this->event->trainings->name);
        $sheet->setCellValue('B5', 'TANGGAL :');
        $sheet->setCellValue('D5', $this->event->startDateFormat() . ' - ' . $this->event->endDateFormat());
        $sheet->setCellValue('E4', 'WAKTU :');
        $sheet->setCellValue('F4', $this->event->start_time . '-' . $this->event->end_time);
        $sheet->setCellValue('E5', 'TEMPAT :');
        $sheet->setCellValue('F5', $this->event->locations->name);

        $sheet->getStyle('B4:G5')->applyFromArray([
            'font' => ['bold' => true, 'size' => 12],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'outline' => ['borderStyle' => Border::BORDER_THIN],
            ],
        ]);
        $sheet->getStyle('D4:D5')->applyFromArray([
            'borders' => [
                'outline' => ['borderStyle' => Border::BORDER_THIN],
            ],
        ]);
        $sheet->getStyle('E4:E5')->applyFromArray([
            'borders' => [
                'outline' => ['borderStyle' => Border::BORDER_THIN],
            ],
        ]);

        $sheet->mergeCells('F7:G7');


        $sheet->getStyle('B7:G7')->applyFromArray([
            'font' => ['bold' => true],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => 'FFFF00',
                ],
            ],
        ]);


        $sheet->getRowDimension(2)->setRowHeight(26);
        $sheet->getRowDimension(3)->setRowHeight(26);

        $columnPixels = [
            'A' => 8,
            'B' => 29,
            'C' => 56,
            'D' => 226,
            'E' => 319,
            'F' => 111,
            'G' => 111,
        ];

        foreach ($columnPixels as $col => $px) {
            $sheet->getColumnDimension($col)->setWidth($px / 7);
        }

        $pages = $this->participants->chunk(30);

        $number = 1;
        $currentRow = 8;

        foreach ($pages as $pageIndex => $pageParticipants) {

            if ($pageIndex > 0) {
                $sheet->setBreak("A{$currentRow}", Worksheet::BREAK_ROW);
                $currentRow = 8;
            }

            $currentRow = $this->renderParticipants(
                $sheet,
                $pageParticipants->values(),
                $currentRow,
                $number
            );

            $number += $pageParticipants->count();
        }

        $sheet->mergeCells('B38:G38');
        $sheet->setCellValue('B38', 'INSTRUKTUR :');
        $sheet->getStyle('B38:G38')->applyFromArray([
            'borders' => [
                'outline' => ['borderStyle' => Border::BORDER_THIN],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_JUSTIFY,
                'vertical'   => Alignment::VERTICAL_CENTER,
            ],
            'font' => [
                'bold' => true,
                'size' => 12,
                'name' => 'Tahoma'
            ],
        ]);

        $headerNewTable = ['NO', 'NPK', 'NAMA', 'INSTANSI/DEPARTMENT', 'TANDA TANGAN'];
        $sheet->mergeCells('F39:G39');
        $startRow = 39;
        $startCol = 'B';

        foreach ($headerNewTable as $index => $title) {
            $col = chr(ord($startCol) + $index);
            $sheet->setCellValue("{$col}{$startRow}", $title);

            $sheet->getStyle("{$col}{$startRow}")->applyFromArray([
                'font' => ['bold' => true, 'size' => 12, 'name' => 'Tahoma'],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
                'borders' => [
                    'allBorders' => ['borderStyle' => Border::BORDER_THIN],
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'FFFF00'],
                ],
            ]);
        }

        $currentRow = $startRow + 1;
        $this->renderTrainer(
            $sheet,
            $this->trainers,
            $this->organizers,
            $currentRow,
            1
        );
    }

    private function renderParticipants(Worksheet $sheet, $participants, int $startRow, int $startNumber)
    {
        $totalPeserta = 30;
        $rowHeight    = 21;

        for ($i = 0; $i < $totalPeserta; $i++) {

            $row = $startRow + $i;
            $sheet->getRowDimension($row)->setRowHeight($rowHeight);

            $sheet->setCellValue("B{$row}", $startNumber + $i);

            if (isset($participants[$i])) {
                $p = $participants[$i];

                $sheet->setCellValue("C{$row}", $p->npk ?? '');
                $sheet->setCellValue(
                    "D{$row}",
                    mb_strtoupper($p->user->full_name ?? '')
                );
                $sheet->setCellValue(
                    "E{$row}",
                    mb_strtoupper($p->user->dept ?? '')
                );
            } else {
                $sheet->setCellValue("C{$row}", '');
                $sheet->setCellValue("D{$row}", '');
                $sheet->setCellValue("E{$row}", '');
            }

            $sheet->getStyle("B{$row}:E{$row}")->applyFromArray([
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical'   => Alignment::VERTICAL_CENTER,
                ],
                'font' => [
                    'name' => 'Tahoma',
                    'size' => 10
                ],
                'borders' => [
                    'allBorders' => ['borderStyle' => Border::BORDER_THIN],
                ],
            ]);
        }

        $absen = 1;

        for ($i = 0; $i < $totalPeserta; $i += 2) {

            $rowStart = $startRow + $i;
            $rowEnd   = $rowStart + 1;

            $sheet->mergeCells("F{$rowStart}:F{$rowEnd}");
            $sheet->setCellValue("F{$rowStart}", $absen);

            if ($absen + 1 <= 30) {
                $sheet->mergeCells("G{$rowStart}:G{$rowEnd}");
                $sheet->setCellValue("G{$rowStart}", $absen + 1);
            }

            $sheet->getStyle("F{$rowStart}:G{$rowEnd}")->applyFromArray([
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_JUSTIFY,
                    'vertical'   => Alignment::VERTICAL_TOP,
                ],
                'borders' => [
                    'allBorders' => ['borderStyle' => Border::BORDER_THIN],
                ],
            ]);

            $absen += 2;
        }

        return $startRow + $totalPeserta;
    }

    private function renderTrainer(Worksheet $sheet, $trainers, $organizers, int $startRow, int $startNumber)
    {
        $totalTrainer = 4;
        $rowHeight    = 21;

        $absen = 1;

        for ($i = 0; $i < $totalTrainer; $i++) {
            $row = $startRow + $i;
            $sheet->getRowDimension($row)->setRowHeight($rowHeight);

            $sheet->setCellValue("B{$row}", $startNumber + $i);

            if (isset($trainers[$i])) {
                $t = $trainers[$i];
                $sheet->setCellValue("C{$row}", $t->npk ?? '');
                $sheet->setCellValue("D{$row}", mb_strtoupper($t->name ?? ''));
                $sheet->setCellValue("E{$row}", mb_strtoupper($t->user->dept ?? ($organizers->name ?? '-')));
            } else {
                $sheet->setCellValue("C{$row}", '');
                $sheet->setCellValue("D{$row}", '');
                $sheet->setCellValue("E{$row}", '');
            }

            $sheet->getStyle("B{$row}:E{$row}")->applyFromArray([
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical'   => Alignment::VERTICAL_CENTER,
                ],
                'font' => [
                    'name' => 'Tahoma',
                    'size' => 10
                ],
                'borders' => [
                    'allBorders' => ['borderStyle' => Border::BORDER_THIN],
                ],
            ]);
        }

        for ($i = 0; $i < $totalTrainer; $i += 2) {
            $rowStart = $startRow + $i;
            $rowEnd   = $rowStart + 1;

            $sheet->mergeCells("F{$rowStart}:F{$rowEnd}");
            $sheet->setCellValue("F{$rowStart}", $absen);

            if ($absen + 1 <= $totalTrainer) {
                $sheet->mergeCells("G{$rowStart}:G{$rowEnd}");
                $sheet->setCellValue("G{$rowStart}", $absen + 1);
            }

            $sheet->getStyle("F{$rowStart}:G{$rowEnd}")->applyFromArray([
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_JUSTIFY,
                    'vertical'   => Alignment::VERTICAL_TOP,
                ],
                'borders' => [
                    'allBorders' => ['borderStyle' => Border::BORDER_THIN],
                ],
            ]);

            $absen += 2;
        }
    }
}
