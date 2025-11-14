<?php

namespace App\Imports;

use App\Models\Training;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;

class MasterTrainingImport implements ToModel, WithHeadingRow, WithMultipleSheets
{
    public function headingRow(): int
    {
        return 1;
    }


    public function boot()
    {
        HeadingRowFormatter::default('slug');
    }

    public function sheets(): array
    {
        return [
            0 => $this,
        ];
    }

    public function model(array $row)
    {
        try {
            return new Training([
                'code' => $row['kode'] ?? null,
                'name' => $row['program_training'] ?? null,
                'desc' => $row['golongan'] ?? null,
                'purpose' => $row['departement'] ?? null,
            ]);
        } catch (\Throwable $e) {
            Log::error('Gagal import baris training: ' . $e->getMessage(), [
                'row_data' => $row,
            ]);
            return null;
        }
    }
}
