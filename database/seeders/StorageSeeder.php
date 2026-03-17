<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Storage;

class StorageSeeder extends Seeder
{
    public function run(): void
    {
        $storages = [
            [
                'hostname' => 'storage-dev-01',
                'data_ip' => '10.10.1.10',
                'platform' => 'VMware',
                'os_name' => 'Ubuntu 22.04',
                'os_internal' => 'Ubuntu Server',
                'operations_system' => 'Linux',
                'internal_ip' => '192.168.1.10',
                'environment' => 'DEV',
                'datacenter' => 'DC-MEX-01',
            ]
        ];

        foreach ($storages as $storage) {
            $record = Storage::updateOrCreate(
                ['hostname' => $storage['hostname']],
                $storage
            );

            Storage::query()
                ->where('hostname', $storage['hostname'])
                ->whereKeyNot($record->id)
                ->delete();
        }
    }
}
