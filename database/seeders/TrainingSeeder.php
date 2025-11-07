<?php

namespace Database\Seeders;

use App\Imports\MasterTrainingImport;
use Illuminate\Database\Seeder;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\TrainingImport;
use App\Models\Training;

class TrainingSeeder extends Seeder
{
    public function run(): void
    {
        // $path = public_path('docs/training.xlsx');

        // Excel::import(new MasterTrainingImport, $path);

        // $this->command->info('✅ TrainingSeeder: data berhasil diimport dari training.xlsx');

        $trainings = [
            [
                'code' => 'TFG1001',
                'name' => 'Process Failure Mode and Effect  Analysis',
                'desc' => 'TFG1001',
                'purpose' => 'Agar trainee memahami keseluruhan proses produksi dan menemukan kemungkinan kegagalan yang akan terjadi melalui serangkaian penggunaan metode-metode PFMEA dan analisanya. Pada akhirnya diharapkan dapat mengoptimalkan tingkat keberhasilan proses produksi',
                'day_duration' => 3,
                'time_duration' => 3
            ],
        ];

        foreach ($trainings as $training) {
            Training::create($training);
        };
    }
}
