<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
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
                'name' => 'editUser',
                'description' => 'Permite editar los usuarios.',
            ],
            [
                'name' => 'deleteUser',
                'description' => 'Permite eliminar los usuarios.',
            ],
        ];

        foreach ($permissions as $permission) {
            $perm = Permission::firstOrCreate(
                ['name' => $permission['name']],
                ['description' => $permission['description']]
            );

            if (! $roleAdmin->hasPermissionTo($perm)) {
                $roleAdmin->givePermissionTo($perm);
            }
        }
    }
}
