<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use App\Models\Owner;
use App\Models\Server;
use App\Models\TypeApplication;

class ServerSeeder extends Seeder
{
    public function run(): void
    {
        $server = Server::updateOrCreate(
            ['vm_according_to_the_vmware' => 'VM-001'],
            [
                'owner_id' => 1,
                'created_by' => 1,
                'created_at' => now(),
                'uuid' => 'd3fbc31d-7a4a-4fcb-9c58-2a20e9aa0001',
                'type_application_id' => 1,
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
                'comments' => 'Servidor principal de produccion',
            ]
        );

        Server::withTrashed()
            ->where('vm_according_to_the_vmware', 'VM-001')
            ->whereKeyNot($server->id)
            ->get()
            ->each
            ->forceDelete();

        $applianceTypeId = TypeApplication::where('name_application', 'Apliance')->value('id');
        $owner = Owner::query()->first();

        if ($applianceTypeId && $owner) {
            $apl = Server::updateOrCreate(
                ['vm_according_to_the_vmware' => 'APL-001'],
                [
                    'owner_id' => $owner->id,
                    'created_by' => 1,
                    'created_at' => now(),
                    'uuid' => 'e4fbc31d-7a4a-4fcb-9c58-2a20e9aa0001',
                    'type_application_id' => $applianceTypeId,
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
                ->whereKeyNot($apl->id)
                ->get()->each->forceDelete();

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
                    'state' => 'poweredOff',
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
                    'state' => 'poweredOff',
                ],
            ];

            foreach ($appliancesOff as $data) {
                Server::updateOrCreate(
                    ['vm_according_to_the_vmware' => $data['vm_according_to_the_vmware']],
                    array_merge($data, [
                        'owner_id' => $owner->id,
                        'created_by' => 1,
                        'type_application_id' => $applianceTypeId,
                    ])
                );
            }
        }
    }
}
