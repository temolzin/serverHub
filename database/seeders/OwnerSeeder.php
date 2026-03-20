<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Owner;

class OwnerSeeder extends Seeder
{
  public function run(): void
  {
    Owner::firstOrCreate([
      'name' => 'Luis',
      'created_by' => 1,
      'created_at' => now(),
      'last_name' => 'Pérez',
      'email' => 'luis.perez@empresa.com',
      'number_phone' => '5512345678',
    ]);

    Owner::firstOrCreate([
      'name' => 'Ana',
      'created_by' => 1,
      'created_at' => now(),
      'last_name' => 'López',
      'email' => 'ana.lopez@empresa.com',
      'number_phone' => '5587654321',
    ]);

    Owner::firstOrCreate([
      'name' => 'Carlos',
      'created_by' => 1,
      'created_at' => now(),
      'last_name' => 'Ramírez',
      'email' => 'carlos.ramirez@empresa.com',
      'number_phone' => '554678124643',
    ]);
  }
}
