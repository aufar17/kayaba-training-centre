<?php

namespace App\Imports;

use App\Models\Departement;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class DepartementImport implements ToModel, WithHeadingRow, WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            1 => new self(),
        ];
    }

    public function model(array $row)
    {
        return new Departement([
            'code' => $row['code'],
            'name' => $row['name'],
        ]);
    }
}
