<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Database;
use App\Models\Instance;

class ServerDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $instances = Instance::with('server')->get();

        foreach ($instances as $instance) {
            Database::create([
                'instance_id'   => $instance->id,
                'owner_id'     => $instance->server->owner_id,
                'name'        => 'db_' . $instance->id,
                'type'        => 'MySQL',
                'status'      => 'Active',
                'port'        => 3306,
                'version'     => '8.0',
                'last_update' => now(),
                'comments'    => 'Base generada automáticamente',
            ]);
        }
    }
}
