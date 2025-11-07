<?php

namespace Database\Seeders;

use App\Models\Organizer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrganizerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $organizers = [
            [
                'code' => 'ORG001',
                'name' => 'Kayaba',
            ],
        ];

        foreach ($organizers as $organizer) {
            Organizer::create($organizer);
        };
    }
}
