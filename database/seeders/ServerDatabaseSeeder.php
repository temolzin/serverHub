<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Database;
use App\Models\Instance;

class ServerDatabaseSeeder extends Seeder
{
    private const MAX_SEEDED_DATABASES = 3;

    public function run(): void
    {
        $instances = Instance::with('server:id,owner_id')
            ->orderBy('id')
            ->limit(self::MAX_SEEDED_DATABASES)
            ->get();

        foreach ($instances as $instance) {
            if (!$instance->server?->owner_id) {
                continue;
            }

            $database = Database::updateOrCreate(
                [
                    'instance_id' => $instance->id,
                    'name' => 'db_' . $instance->id,
                ],
                [
                    'owner_id' => $instance->server->owner_id,
                    'type' => 'MySQL',
                    'status' => 'Active',
                    'port' => 3306,
                    'version' => '8.0',
                    'last_update' => now(),
                    'comments' => 'Base generada automaticamente',
                ]
            );

            Database::withTrashed()
                ->where('instance_id', $instance->id)
                ->where('name', 'db_' . $instance->id)
                ->whereKeyNot($database->id)
                ->get()
                ->each
                ->forceDelete();
        }
    }
}
