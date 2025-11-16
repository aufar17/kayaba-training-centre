<?php

namespace App\Imports;

use App\Models\Department;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class DepartmentImport implements ToModel, WithHeadingRow, WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            0 => new self(),
        ];
    }

    public function model(array $row)
    {
        return new Department([
            'code' => $row['code'],
            'name' => $row['name'],
        ]);
    }
}
