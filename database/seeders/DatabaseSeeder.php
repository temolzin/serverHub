<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Database;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
            UserSeeder::class,
            OwnerSeeder::class,
            GcpMachineSeeder::class,
            TypeApplicationSeeder::class,
            ServerSeeder::class,
            ServerDatabaseSeeder::class,
        ]);
    }
}
