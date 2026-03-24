<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Owner;

class OwnerSeeder extends Seeder
{
    public function run(): void
    {
        Owner::firstOrCreate(
            ['email' => 'luis.perez@empresa.com'],
            [
                'name' => 'Luis',
                'created_by' => 1,
                'last_name' => 'Pérez',
                'number_phone' => '5512345678',
                'created_at' => now(),
            ]
        );

        Owner::firstOrCreate(
            ['email' => 'ana.lopez@empresa.com'],
            [
                'name' => 'Ana',
                'created_by' => 1,
                'last_name' => 'López',
                'number_phone' => '5587654321',
                'created_at' => now(),
            ]
        );

        Owner::firstOrCreate(
            ['email' => 'carlos.ramirez@empresa.com'],
            [
                'name' => 'Carlos',
                'created_by' => 1,
                'last_name' => 'Ramírez',
                'number_phone' => '554678124643',
                'created_at' => now(),
            ]
        );
    }
}
