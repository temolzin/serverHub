<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Server;
use App\Models\Instance;

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
                'memory' => rand(2048, 16384),
                'version' => '15.' . rand(0, 5),
                'edition' => collect(['Standard', 'Enterprise', 'Express'])->random(),
          ]);
        }
    }
}
