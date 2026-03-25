<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
  public function run(): void
  {
    $user = User::updateOrCreate(
      ['email' => 'jose@gmail.com'],
      [
        'name' => 'Jose',
        'last_name' => 'Perez',
        'password' => Hash::make('12345'),
        'email_verified_at' => now(),
      ]
    );
      if (! $user->hasRole('Admin')) {
        $user->assignRole('Admin');
    }
  }
}
