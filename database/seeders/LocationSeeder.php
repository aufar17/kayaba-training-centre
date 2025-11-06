<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $locations = [
            [
                'code' => 'LOC0001',
                'name' => 'Training Centre',
            ],
        ];

        foreach ($locations as $loc) {
            Location::create($loc);
        };
    }
}
