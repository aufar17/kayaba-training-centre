<?php

namespace Database\Seeders;

use App\Imports\DepartmentImport;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Maatwebsite\Excel\Facades\Excel;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Excel::import(new DepartmentImport, public_path('docs/Dept.xlsx'));
        $this->command->info('Department imported successfully!');
    }
}
