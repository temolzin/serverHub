<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Server;
use App\Models\ServerDatabase;

class ServerDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $server = Server::first();

        if (!$server) {
            return;
        }

        ServerDatabase::create([
            'server_id' => $server->id,
            'name' => 'main_db',
            'type' => 'mysql',
            'status' => 'active',
            'comments' => 'Primary database',
            'port' => 3306,
            'version' => '8.0',
            'last_update' => now(),
        ]);
    }
}
