<?php

namespace Database\Seeders;

use App\Imports\MasterTrainingImport;
use Illuminate\Database\Seeder;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\TrainingImport;

class TrainingSeeder extends Seeder
{
    public function run(): void
    {
        $path = public_path('docs/training.xlsx');

        Excel::import(new MasterTrainingImport, $path);

        $this->command->info('✅ TrainingSeeder: data berhasil diimport dari training.xlsx');
    }
}
