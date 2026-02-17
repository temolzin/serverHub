<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Database;
use App\Models\Server;

class ServerDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $servers = Server::all();

        foreach ($servers as $server) {
            Database::create([
                'server_id'   => $server->id,
                'name'        => 'db_' . $server->id,
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
