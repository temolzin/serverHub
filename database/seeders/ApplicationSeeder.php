<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Application;
use App\Models\GcpMachine;
use App\Models\Owner;
use App\Models\Server;

class ApplicationSeeder extends Seeder
{
  public function run(): void
  {
    $owner = Owner::first();
    $server = Server::first();
    $gcpMachine = GcpMachine::first();

    if (!$owner || !$server) {
      return;
    }

    Application::create([
      'owner_id' => $owner->id,
      'server_id' => $server->id,
      'gcp_machine_id' => $gcpMachine?->id,
      'name' => 'ServerHub API',
      'version' => '1.0.0',
      'status' => 'production',
      'type' => 'api',
      'comments' => 'Main backend application',
      'processes' => 'php-fpm, nginx',
      'assigned_memory' => 2048,
      'installation_route' => '/var/www/serverhub',
      'latest_security_patch' => now(),
      'user_service' => 'www-data',
      'cron_jobs' => '0 2 * * * php artisan schedule:run',
    ]);
  }
}
