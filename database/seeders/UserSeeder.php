<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminJose = User::updateOrCreate(
            ['email' => 'jose@gmail.com'],
            [
                'name' => 'Jose',
                'last_name' => 'Perez',
                'password' => Hash::make('12345'),
                'email_verified_at' => now(),
            ]
        );
        $adminJose->syncRoles(['Admin']);

        $adminAna = User::updateOrCreate(
            ['email' => 'ana@gmail.com'],
            [
                'name' => 'Ana',
                'last_name' => 'Lopez',
                'password' => Hash::make('12345'),
                'email_verified_at' => now(),
            ]
        );
        $adminAna->syncRoles(['Admin']);
    }
}
