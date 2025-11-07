<?php

namespace Database\Seeders;

use App\Models\Trainer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TrainerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $trainers = [
            [
                'code' => 'TA0001',
                'name' => 'Aufar',
            ],
        ];

        foreach ($trainers as $trainer) {
            Trainer::create($trainer);
        };
    }
}
