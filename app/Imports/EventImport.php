<?php

// namespace App\Imports;

// use PhpOffice\PhpSpreadsheet\IOFactory;

// class EventImport
// {
//     public static function readAllSheets($file): array
//     {
//         $spreadsheet = IOFactory::load($file->getPathname());
//         $sheet = $spreadsheet->getSheetByName('2022');

//         if (!$sheet) {
//             throw new \Exception("Sheet 2022 tidak ditemukan");
//         }

//         $rows = $sheet->toArray(null, true, true, true);

//         $headers = array_map(fn($h) => strtolower(trim($h)), $rows[1]);

//         $cleanRows = [];

//         foreach ($rows as $i => $row) {
//             if ($i == 1) continue; // skip header

//             $cleanRows[] = array_combine($headers, $row);
//         }

//         return $cleanRows;
//     }
// }


// <?php

namespace App\Imports;

use PhpOffice\PhpSpreadsheet\IOFactory;

class EventImport
{
    /**
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @return array
     */
    public static function readAllSheets($file): array
    {
        $spreadsheet = IOFactory::load($file->getPathname());
        $sheets = $spreadsheet->getSheetNames();

        $allRows = [];

        foreach ($sheets as $sheetName) {
            $sheet = $spreadsheet->getSheetByName($sheetName);
            if (!$sheet) continue;

            $rows = $sheet->toArray(null, true, true, true);
            if (count($rows) < 2) continue; // skip jika kosong atau cuma header

            // ambil header dari baris pertama
            $headers = array_map(fn($h) => strtolower(trim($h)), $rows[1]);

            foreach ($rows as $i => $row) {
                if ($i == 1) continue; // skip header
                $allRows[] = array_combine($headers, $row);
            }
        }
        return $allRows;
    }
}
