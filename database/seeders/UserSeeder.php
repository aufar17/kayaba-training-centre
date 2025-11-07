<?php

namespace Database\Seeders;

use App\Models\Auth\CTUser;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'npk' => '212121',
                'full_name' => 'Aufar HRD',
                'approved' => 1,
                'dept' => 'HRD',
                'sect' => '12',
                'golongan' => '4',
                'acting' => '1',
                'pwd' => '123',
            ],
        ];

        foreach ($users as $user) {
            CTUser::create($user);
        };
    }
}
