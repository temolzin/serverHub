<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Instance;
use App\Models\Database;
use App\Models\Owner;

class ServerDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $instances = Instance::all();
        $owners = Owner::all();

      foreach ($instances as $instance) {
          Database::create([
              'instance_id' => $instance->id,
              'owner_id'    => $owners->random()->id,
              'name' => 'db_' . rand(1, 100),
              'type' => collect(['MySQL', 'PostgreSQL'])->random(),
              'status' => 'Active',
              'port' => rand(3000, 6000),
              'last_update' => now(),
              'comments' => 'Base generada automáticamente',
          ]);
        }
    }
}
