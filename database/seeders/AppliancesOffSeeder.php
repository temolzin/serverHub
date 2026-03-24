<?php

namespace Database\Seeders;

use App\Models\Owner;
use App\Models\Server;
use App\Http\Controllers\ApplianceController;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class AppliancesOffSeeder extends Seeder
{
    public function run(): void
    {
        $owner = Owner::query()->first();
        $typeApplicationId = ApplianceController::getApplianceTypeApplicationId();

        if (!$owner) {
            return;
        }

        $appliancesOff = [
            [
                'uuid' => 'd7c7d8d1-0d1a-4a0b-a111-7cc9e8100001',
                'vm_according_to_the_vmware' => 'APL-OFF-001',
                'dns_name' => 'apl-off-01.empresa.com',
                'primary_ip_address' => null,
                'ip_user' => '10.10.10.31',
                'ip_monitoring' => '10.10.20.31',
                'environment' => 'DR',
                'datacenter' => 'DC-MEX-01',
                'os_version_internal' => 'Debian 11',
                'os_according_to_the_vmware' => 'Debian Linux (64-bit)',
                'hostname_internal' => 'apl-off-01',
                'ram_memory' => 4096,
                'swap_memory' => 2048,
                'latest_security_patch' => Carbon::parse('2025-12-15')->toDateString(),
                'other_ips' => '10.10.30.31',
                'comments' => 'Apliance apagado por inactividad',
            ],
            [
                'uuid' => 'd7c7d8d1-0d1a-4a0b-a111-7cc9e8100002',
                'vm_according_to_the_vmware' => 'APL-OFF-002',
                'dns_name' => 'apl-off-02.empresa.com',
                'primary_ip_address' => null,
                'ip_user' => '10.11.10.32',
                'ip_monitoring' => '10.11.20.32',
                'environment' => 'QA',
                'datacenter' => 'DC-QRO-01',
                'os_version_internal' => 'CentOS 8',
                'os_according_to_the_vmware' => 'CentOS Linux (64-bit)',
                'hostname_internal' => 'apl-off-02',
                'ram_memory' => 8192,
                'swap_memory' => 4096,
                'latest_security_patch' => Carbon::parse('2025-11-28')->toDateString(),
                'other_ips' => null,
                'comments' => 'Apliance apagado obsoleto',
            ]
        ];

        foreach ($appliancesOff as $applianceOff) {
            Server::updateOrCreate(
                [
                    'vm_according_to_the_vmware' => $applianceOff['vm_according_to_the_vmware'],
                    'state' => 'poweredOff',
                ],
                array_merge($applianceOff, [
                    'owner_id' => $owner->id,
                    'created_by' => 1,
                    'type_application_id' => $typeApplicationId,
                ])
            );
        }
    }
}
