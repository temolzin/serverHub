<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $user1 = User::updateOrCreate(
            ['email' => 'jose@gmail.com'],
            [
                'name' => 'Jose',
                'last_name' => 'Perez',
                'password' => Hash::make('12345'),
                'email_verified_at' => now(),
            ]
        );
        $user1->syncRoles(['Admin']);

        $user2 = User::updateOrCreate(
            ['email' => 'ana@gmail.com'],
            [
                'name' => 'Ana',
                'last_name' => 'Lopez',
                'password' => Hash::make('12345'),
                'email_verified_at' => now(),
            ]
        );
        $user2->syncRoles(['Admin']);
    }
}
