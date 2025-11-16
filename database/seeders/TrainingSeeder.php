<?php

namespace Database\Seeders;

use App\Imports\MasterTrainingImport;
use Illuminate\Database\Seeder;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Training;

class TrainingSeeder extends Seeder
{
    public function run()
    {
        $path = public_path('docs/Matrix.xlsx');

        if (!file_exists($path)) {
            $this->command->error("File tidak ditemukan: {$path}");
            return;
        }

        try {
            Excel::import(new MasterTrainingImport(), $path);
            $this->command->info('Training imported successfully!');
        } catch (\Exception $e) {
            $this->command->error('Import gagal: ' . $e->getMessage());
        }
    }
}
