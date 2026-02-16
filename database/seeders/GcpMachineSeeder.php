<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\GcpMachine;
use App\Models\Owner;
use Illuminate\Support\Str;

class GcpMachineSeeder extends Seeder
{
  public function run(): void
  {
    $owner = Owner::first();

    if (!$owner) {
      return;
    }

    GcpMachine::create([
      'uuid' => (string) Str::uuid(),
      'project_name' => 'serverhub-prod',
      'environment' => 'production',
      'machine_name' => 'gcp-vm-01',
      'machine_internal_name' => 'vm-int-01',
      'operations_system' => 'Ubuntu 22.04',
      'latest_security_patch' => '2025-01-15',
      'internal_ip' => '10.128.0.2',
      'alias_ip' => '35.233.120.1',
      'alias2_ip' => null,
      'alias3_ip' => null,
      'other_ips' => null,
      'kernel_version' => '5.15.0',
      'ram_memory' => 16384,
      'swap_memory' => 4096,
      'owner_id' => $owner->id,
    ]);

    GcpMachine::create([
      'uuid' => (string) Str::uuid(),
      'project_name' => 'serverhub-staging',
      'environment' => 'staging',
      'machine_name' => 'gcp-vm-02',
      'machine_internal_name' => 'vm-int-02',
      'operations_system' => 'Debian 12',
      'latest_security_patch' => '2025-02-10',
      'internal_ip' => '10.128.0.3',
      'alias_ip' => '34.122.45.10',
      'alias2_ip' => '34.122.45.11',
      'alias3_ip' => '34.122.45.12',
      'other_ips' => '192.168.1.10, 192.168.1.11',
      'kernel_version' => '6.1.0',
      'ram_memory' => 32768,
      'swap_memory' => 8192,
      'owner_id' => $owner->id,
    ]);
  }
}
