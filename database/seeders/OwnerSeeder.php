<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Owner;

class OwnerSeeder extends Seeder
{
  public function run(): void
  {
    Owner::insert([
      [
        'name' => 'Juan',
        'last_name' => 'Pérez',
        'email' => 'juan.perez@empresa.com',
        'number_phone' => '5512345678',
      ],
      [
        'name' => 'Ana',
        'last_name' => 'López',
        'email' => 'ana.lopez@empresa.com',
        'number_phone' => '5587654321',
      ],
      [
        'name' => 'Carlos',
        'last_name' => 'Ramírez',
        'email' => 'carlos.ramirez@empresa.com',
        'number_phone' => null,
      ],
    ]);
  }
}
