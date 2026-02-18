<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
  public function run(): void
  {
    $this->call(RoleSeeder::class);
    $this->call(UserSeeder::class);
    $this->call(OwnerSeeder::class);
    $this->call(GcpMachineSeeder::class);
    $this->call(TypeApplicationSeeder::class);
    $this->call(ServerSeeder::class);
    $this->call(InstanceSeeder::class);
    $this->call(ServerDatabaseSeeder::class);
    $this->call(ApplicationSeeder::class);
  }
}
