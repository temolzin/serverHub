<?php

namespace Database\Seeders;

use App\Models\Owner;
use App\Models\Server;
use App\Models\TypeApplication;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class ServersOffSeeder extends Seeder
{
    public function run(): void
    {
        $owner = Owner::query()->first();
        $typeApplication = TypeApplication::query()->first();

        if (!$owner || !$typeApplication) {
            return;
        }

        $serversOff = [
            [
                'uuid' => 'c6c7d8d1-0d1a-4a0b-a111-7cc9e8100001',
                'vm_according_to_the_vmware' => 'VM-OFF-001',
                'dns_name' => 'srv-off-01.empresa.com',
                'primary_ip_address' => null,
                'ip_user' => '10.10.10.21',
                'ip_monitoring' => '10.10.20.21',
                'environment' => 'DR',
                'datacenter' => 'DC-MEX-01',
                'os_version_internal' => 'RHEL 8.10',
                'os_according_to_the_vmware' => 'Red Hat Enterprise Linux 8 (64-bit)',
                'hostname_internal' => 'srv-off-01',
                'ram_memory' => 8192,
                'swap_memory' => 4096,
                'latest_security_patch' => Carbon::parse('2025-12-15')->toDateString(),
                'other_ips' => '10.10.30.21,10.10.40.21',
                'comments' => 'Servidor apagado por mantenimiento programado',
            ],
            [
                'uuid' => 'c6c7d8d1-0d1a-4a0b-a111-7cc9e8100002',
                'vm_according_to_the_vmware' => 'VM-OFF-002',
                'dns_name' => 'srv-off-02.empresa.com',
                'primary_ip_address' => null,
                'ip_user' => '10.11.10.22',
                'ip_monitoring' => '10.11.20.22',
                'environment' => 'QA',
                'datacenter' => 'DC-QRO-01',
                'os_version_internal' => 'Ubuntu 22.04',
                'os_according_to_the_vmware' => 'Ubuntu Linux (64-bit)',
                'hostname_internal' => 'srv-off-02',
                'ram_memory' => 16384,
                'swap_memory' => 4096,
                'latest_security_patch' => Carbon::parse('2025-11-28')->toDateString(),
                'other_ips' => null,
                'comments' => 'Servidor apagado por cambio de plataforma',
            ],
            [
                'uuid' => 'c6c7d8d1-0d1a-4a0b-a111-7cc9e8100003',
                'vm_according_to_the_vmware' => 'VM-OFF-003',
                'dns_name' => 'srv-off-03.empresa.com',
                'primary_ip_address' => null,
                'ip_user' => '10.12.10.23',
                'ip_monitoring' => '10.12.20.23',
                'environment' => 'PROD',
                'datacenter' => 'DC-TUL-01',
                'os_version_internal' => 'Oracle Linux 9',
                'os_according_to_the_vmware' => 'Oracle Linux 9 (64-bit)',
                'hostname_internal' => 'srv-off-03',
                'ram_memory' => 32768,
                'swap_memory' => 8192,
                'latest_security_patch' => Carbon::parse('2025-10-05')->toDateString(),
                'other_ips' => '10.12.30.23',
                'comments' => 'Servidor fuera de uso temporal',
            ],
        ];

        foreach ($serversOff as $serverOff) {
            Server::updateOrCreate(
                [
                    'vm_according_to_the_vmware' => $serverOff['vm_according_to_the_vmware'],
                    'state' => 'poweredOff',
                ],
                [
                    'owner_id' => $owner->id,
                    'type_application_id' => $typeApplication->id,
                    'uuid' => $serverOff['uuid'],
                    'dns_name' => $serverOff['dns_name'],
                    'primary_ip_address' => $serverOff['primary_ip_address'],
                    'environment' => $serverOff['environment'],
                    'datacenter' => $serverOff['datacenter'],
                    'os_according_to_the_vmware' => $serverOff['os_according_to_the_vmware'],
                    'os_version_internal' => $serverOff['os_version_internal'],
                    'hostname_internal' => $serverOff['hostname_internal'],
                    'ip_user' => $serverOff['ip_user'],
                    'ip_monitoring' => $serverOff['ip_monitoring'],
                    'other_ips' => $serverOff['other_ips'],
                    'ram_memory' => $serverOff['ram_memory'],
                    'swap_memory' => $serverOff['swap_memory'],
                    'latest_security_patch' => $serverOff['latest_security_patch'],
                    'comments' => $serverOff['comments'],
                ]
            );
        }

        $applianceTypeId = TypeApplication::where('name_application', 'Apliance')->value('id');

        if ($applianceTypeId) {
            $appliancesOff = [
                [
                    'uuid' => 'd7c7d8d1-0d1a-4a0b-a111-7cc9e8100001',
                    'vm_according_to_the_vmware' => 'APL-OFF-001',
                    'dns_name' => 'apl-off-01.empresa.com',
                    'primary_ip_address' => null,
                    'environment' => 'N/A',
                    'datacenter' => 'DC-MEX-01',
                    'os_according_to_the_vmware' => 'Debian Linux (64-bit)',
                    'ram_memory' => 0,
                    'swap_memory' => 0,
                    'latest_security_patch' => Carbon::parse('2025-12-15')->toDateString(),
                    'comments' => 'Apliance apagado por inactividad',
                ],
                [
                    'uuid' => 'd7c7d8d1-0d1a-4a0b-a111-7cc9e8100002',
                    'vm_according_to_the_vmware' => 'APL-OFF-002',
                    'dns_name' => 'apl-off-02.empresa.com',
                    'primary_ip_address' => null,
                    'environment' => 'N/A',
                    'datacenter' => 'DC-QRO-01',
                    'os_according_to_the_vmware' => 'CentOS Linux (64-bit)',
                    'ram_memory' => 0,
                    'swap_memory' => 0,
                    'latest_security_patch' => Carbon::parse('2025-11-28')->toDateString(),
                    'comments' => 'Apliance apagado obsoleto',
                ],
            ];

            foreach ($appliancesOff as $data) {
                Server::updateOrCreate(
                    ['vm_according_to_the_vmware' => $data['vm_according_to_the_vmware'], 'state' => 'poweredOff'],
                    array_merge($data, [
                        'owner_id' => $owner->id,
                        'created_by' => 1,
                        'type_application_id' => $applianceTypeId,
                        'state' => 'poweredOff',
                    ])
                );
            }
        }
    }
}
