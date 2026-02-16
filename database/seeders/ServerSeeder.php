<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Server;
use Illuminate\Support\Str;

class ServerSeeder extends Seeder
{
  public function run(): void
  {
    Server::create([
      'owner_id' => 1,
      'uuid' => (string) Str::uuid(),
      'type_application_id' => 1,
      'vm_according_to_the_vmware' => 'VM-001',
      'state' => true,
      'dns_name' => 'server01.empresa.com',
      'primary_ip_address' => '192.168.1.10',
      'environment' => 'production',
      'datacenter' => 'DC-MEX-01',
      'os_according_to_the_vmware' => 'Ubuntu',
      'os_version_internal' => '22.04',
      'hostname_internal' => 'srv-prod-01',
      'ip_user' => '192.168.1.11',
      'ip_monitoring' => '192.168.1.12',
      'other_ips' => '192.168.1.13,192.168.1.14',
      'ram_memory' => 16384,
      'swap_memory' => 8192,
      'latest_security_patch' => now(),
      'comments' => 'Servidor principal de producción'
    ]);
  }
}
