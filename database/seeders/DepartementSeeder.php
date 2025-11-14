<?php

namespace Database\Seeders;

use App\Imports\DepartementImport;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Maatwebsite\Excel\Facades\Excel;

class DepartementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Excel::import(new DepartementImport, public_path('docs/Matriks Training.xlsx'));
        $this->command->info('Users imported successfully!');
    }
}
