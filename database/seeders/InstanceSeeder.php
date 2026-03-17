<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Instance;
use App\Models\Server;

class InstanceSeeder extends Seeder
{
    private const MAX_SEEDED_INSTANCES = 3;

    public function run(): void
    {
        $servers = Server::query()
            ->select('id')
            ->orderBy('id')
            ->limit(self::MAX_SEEDED_INSTANCES)
            ->get();

        foreach ($servers as $server) {
            $instance = Instance::updateOrCreate(
                [
                    'server_id' => $server->id,
                    'version'   => '8.0',
                    'edition'   => 'Enterprise',
                ],
                [
                    'memory' => 4096,
                ]
            );

            Instance::withTrashed()
                ->where('server_id', $server->id)
                ->where('version', '8.0')
                ->where('edition', 'Enterprise')
                ->whereKeyNot($instance->id)
                ->get()
                ->each
                ->forceDelete();
        }
    }
}
