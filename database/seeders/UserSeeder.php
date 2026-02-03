<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
  public function run(): void
  {
    User::updateOrCreate(
      ['email' => 'user@serverhub.com'],
      [
        'name' => 'admin',
        'password' => Hash::make('password123'),
        'email_verified_at' => now(),
      ]
    );
  }
}
