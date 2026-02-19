<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Instance;
use App\Models\Server;

class InstanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
      $servers = Server::all();
        foreach ($servers as $server) {
            Instance::create([
                'server_id' => $server->id,
                'memory'    => 4096,
                'version'   => '8.0',
                'edition'   => 'Enterprise',
            ]);
        }
    }
}
