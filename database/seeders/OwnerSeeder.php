<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Owner;

class OwnerSeeder extends Seeder
{
  public function run(): void
  {
    Owner::updateOrCreate(
      ['email' => 'luis.perez@empresa.com'],
      [
        'name' => 'Luis',
        'last_name' => 'Pérez',
        'number_phone' => '5512345678',
      ]
    );

    Owner::updateOrCreate(
      ['email' => 'ana.lopez@empresa.com'],
      [
        'name' => 'Ana',
        'last_name' => 'López',
        'number_phone' => '5587654321',
      ]
    );

    Owner::updateOrCreate(
      ['email' => 'carlos.ramirez@empresa.com'],
      [
        'name' => 'Carlos',
        'last_name' => 'Ramírez',
        'number_phone' => '554678124643',
      ]
    );
  }
}
