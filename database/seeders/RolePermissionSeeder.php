<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $admin = Role::firstOrCreate(['name' => 'Admin']);

        $permissions = [
            [
                'name' => 'viewOwner',
                'description' => 'Allows viewing owners',
            ],
            [
                'name' => 'viewServer',
                'description' => 'Allows viewing servers',
            ],
            [
                'name' => 'viewGcpMachine',
                'description' => 'Allows viewing GCP machines',
            ],
            [
                'name' => 'viewTypeApplication',
                'description' => 'Allows viewing application types',
            ],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission['name']],
                ['description' => $permission['description']]
            );
        }

        $admin->syncPermissions(
            collect($permissions)->pluck('name')->toArray()
        );
    }
}
