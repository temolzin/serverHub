<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\GcpMachine;
use App\Models\Owner;
use App\Models\Application;
use App\Models\TypeApplication;
use App\Models\Database;

class GcpMachineSeeder extends Seeder
{
    public function run(): void
    {
        $owner = Owner::first();
        $apiType = TypeApplication::where('type_application', 'api')->first();
        $workerType = TypeApplication::where('type_application', 'worker')->first();
        $serverHubApi = Application::where('name', 'ServerHub API')->first();
        $serverHubWeb = Application::where('name', 'ServerHub Web')->first();
        $primaryDatabase = Database::orderBy('id')->first();
        $secondaryDatabase = Database::orderBy('id')->skip(1)->first() ?? $primaryDatabase;

        if (!$owner) {
            return;
        }

        $primaryMachine = GcpMachine::updateOrCreate(
            ['internal_ip' => '10.128.0.2'],
            [
                'uuid' => '9f7a2f9a-2a80-4e11-8c57-6af2a93b1001',
                'project_name' => 'serverhub-prod',
                'type_application_id' => $apiType?->id,
                'application_id' => $serverHubApi?->id,
                'database_id' => $primaryDatabase?->id,
                'state' => 'poweredOn',
                'environment' => 'production',
                'machine_name' => 'gcp-vm-01',
                'machine_internal_name' => 'vm-int-01',
                'operations_system' => 'Ubuntu 22.04',
                'latest_security_patch' => '2025-01-15',
                'alias_ip' => '35.233.120.1',
                'alias2_ip' => null,
                'alias3_ip' => null,
                'other_ips' => null,
                'kernel_version' => '5.15.0',
                'ram_memory' => 16384,
                'swap_memory' => 4096,
                'owner_id' => $owner->id,
            ]
        );

        if ($serverHubApi) {
        $serverHubApi->update(['gcp_machine_id' => $primaryMachine->id]);
        }

        $secondaryMachine = GcpMachine::updateOrCreate(
            ['internal_ip' => '10.128.0.3'],
            [
                'uuid' => '9f7a2f9a-2a80-4e11-8c57-6af2a93b1002',
                'project_name' => 'serverhub-staging',
                'type_application_id' => $workerType?->id,
                'application_id' => $serverHubWeb?->id,
                'database_id' => $secondaryDatabase?->id,
                'state' => 'poweredOff',
                'environment' => 'staging',
                'machine_name' => 'gcp-vm-02',
                'machine_internal_name' => 'vm-int-02',
                'operations_system' => 'Debian 12',
                'latest_security_patch' => '2025-02-10',
                'alias_ip' => '34.122.45.10',
                'alias2_ip' => '34.122.45.11',
                'alias3_ip' => '34.122.45.12',
                'other_ips' => '192.168.1.10, 192.168.1.11',
                'kernel_version' => '6.1.0',
                'ram_memory' => 32768,
                'swap_memory' => 8192,
                'owner_id' => $owner->id,
            ]
        );

            if ($serverHubWeb) {
                $serverHubWeb->update(['gcp_machine_id' => $secondaryMachine->id]);
            }
    }
}
