<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Server;
use App\Http\Controllers\ApplianceController;

class ApplianceSeeder extends Seeder
{
    public function run(): void
    {
        $typeApplicationId = ApplianceController::getApplianceTypeApplicationId();

        $server = Server::updateOrCreate(
            ['vm_according_to_the_vmware' => 'APL-001'],
            [
                'owner_id' => 1,
                'created_by' => 1,
                'created_at' => now(),
                'uuid' => 'e4fbc31d-7a4a-4fcb-9c58-2a20e9aa0001',
                'type_application_id' => $typeApplicationId,
                'state' => 'poweredOn',
                'dns_name' => 'apliance01.empresa.com',
                'primary_ip_address' => '192.168.2.10',
                'environment' => 'produccion',
                'datacenter' => 'DC-MEX-01',
                'os_according_to_the_vmware' => 'CentOS',
                'os_version_internal' => '7.9',
                'hostname_internal' => 'apl-prod-01',
                'ip_user' => '192.168.2.11',
                'ip_monitoring' => '192.168.2.12',
                'other_ips' => '192.168.2.13',
                'ram_memory' => 8192,
                'swap_memory' => 4096,
                'latest_security_patch' => now(),
                'comments' => 'Apliance principal de produccion',
            ]
        );

        Server::withTrashed()
            ->where('vm_according_to_the_vmware', 'APL-001')
            ->whereKeyNot($server->id)
            ->get()
            ->each
            ->forceDelete();
    }
}
