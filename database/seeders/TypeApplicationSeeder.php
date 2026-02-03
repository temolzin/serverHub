<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TypeApplication;

class TypeApplicationSeeder extends Seeder
{
  public function run(): void
  {
    TypeApplication::updateOrCreate(
      ['type_application' => 'web'],
      ['name_application' => 'Web Application']
    );

    TypeApplication::updateOrCreate(
      ['type_application' => 'api'],
      ['name_application' => 'API Service']
    );

    TypeApplication::updateOrCreate(
      ['type_application' => 'worker'],
      ['name_application' => 'Background Worker']
    );
  }
}
