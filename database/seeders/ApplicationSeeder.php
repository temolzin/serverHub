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
    $owner = Owner::query()->first();
    $servers = Server::query()->orderBy('id')->get();
    $gcpMachine = GcpMachine::query()->first();

    $primaryServer = $servers->first();
    $secondaryServer = $servers->skip(1)->first();

    if (!$owner || (!$primaryServer && !$gcpMachine)) {
      return;
    }

    Application::updateOrCreate([
      'name' => 'ServerHub API',
    ], [
      'owner_id' => $owner->id,
      'server_id' => $primaryServer?->id,
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

    Application::updateOrCreate([
      'name' => 'ServerHub Web',
    ], [
      'owner_id' => $owner->id,
      'server_id' => $secondaryServer?->id ?? $primaryServer?->id,
      'gcp_machine_id' => null,
      'name' => 'ServerHub Web',
      'version' => '2.2.1',
      'status' => 'staging',
      'type' => 'web',
      'comments' => 'Frontend application',
      'processes' => 'nginx, node',
      'assigned_memory' => 1024,
      'installation_route' => '/var/www/serverhub-web',
      'latest_security_patch' => now(),
      'user_service' => 'deploy',
      'cron_jobs' => '*/15 * * * * php artisan queue:work --stop-when-empty',
    ]);

    if ($gcpMachine) {
      Application::updateOrCreate([
        'name' => 'Monitoring Agent',
      ], [
        'owner_id' => $owner->id,
        'server_id' => null,
        'gcp_machine_id' => $gcpMachine->id,
        'name' => 'Monitoring Agent',
        'version' => '1.3.0',
        'status' => 'production',
        'type' => 'service',
        'comments' => 'Telemetry and health agent',
        'processes' => 'agentd',
        'assigned_memory' => 512,
        'installation_route' => '/opt/monitoring-agent',
        'latest_security_patch' => now(),
        'user_service' => 'agent',
        'cron_jobs' => '0 */6 * * * /opt/monitoring-agent/refresh',
      ]);
    }
  }
}
