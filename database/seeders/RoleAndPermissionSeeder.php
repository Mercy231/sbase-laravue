<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::create(['name' => 'change-role-permission']);
        Permission::create(['name' => 'edit-users']);
        Permission::create(['name' => 'delete-users']);
        Permission::create(['name' => 'create-posts']);
        Permission::create(['name' => 'edit-posts']);
        Permission::create(['name' => 'delete-posts']);

        Role::create(['name' => 'Admin']);
        Role::create(['name' => 'Manager']);
        Role::create(['name' => 'Guest']);

        $adminRole = Role::findByName("Admin");
        $managerRole = Role::findByName("Manager");
        $guestRole = Role::findByName("Guest");

        $adminRole->givePermissionTo([
            'change-role-permission',
            'edit-users',
            'delete-users',
            'create-posts',
            'edit-posts',
            'delete-posts',
        ]);
        $managerRole->givePermissionTo([
            'edit-users',
            'delete-users',
            'create-posts',
            'edit-posts',
            'delete-posts',
        ]);
        $guestRole->givePermissionTo([
            'create-posts',
            'edit-posts',
            'delete-posts',
        ]);
    }
}
