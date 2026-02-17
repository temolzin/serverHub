<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roleAdmin = Role::firstOrCreate([
            'name' => 'Admin',

            ]);
        $permissions = [
            [
                'name' => 'viewUser',
                'description' => 'Permite ver los usuarios.',
            ],
            [
                'name' => 'viewOwner',
                'description' => 'Permite ver los propietarios.',
            ],
            [
                'name' => 'viewServer',
                'description' => 'Permite ver los servidores.',
            ],
            [
                'name' => 'viewTypeApplication',
                'description' => 'Permite ver los tipos de aplicación.',
            ],
            [
                'name' => 'viewGcpMachine',
                'description' => 'Permite ver las máquinas GCP.',
            ],
            [
                'name' => 'viewApplication',
                'description' => 'Permite ver las aplicaciones.',
            ],
            [
                'name' => 'viewDatabase',
                'description' => 'Permite ver las bases de datos.',
            ],
        ];

        foreach ($permissions as $perm) {
            $permission = Permission::firstOrCreate(
                ['name' => $perm['name']],
                ['description' => $perm['description']]
            );
            $roleAdmin->givePermissionTo($permission);
        }
    }
}
