<?php

namespace App\Imports;

use App\Models\Training;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Illuminate\Support\Facades\Log;

class MasterTrainingImport implements ToModel, WithHeadingRow, WithMultipleSheets
{
    public function headingRow(): int
    {
        return 1;
    }

    public function sheets(): array
    {
        return [
            2 => $this,
        ];
    }

    public function model(array $row)
    {
        try {
            return new Training([
                'code' => $row['kode_training'] ?? null,
                'name' => $row['training'] ?? null,
                'desc' => $row['description'] ?? null,
                'purpose' => $row['purpose'] ?? null,
            ]);
        } catch (\Throwable $e) {
            Log::error('Gagal import baris training: ' . $e->getMessage(), [
                'row_data' => $row,
            ]);
            return null;
        }
    }
}
