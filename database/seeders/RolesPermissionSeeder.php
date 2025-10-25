<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Basic permissions
        $perms = [
        'manage users',
        'manage courses',
        'manage pages',
        'manage files',
        'send newsletters',
        'view admin',
        ];
        foreach ($perms as $p) { Permission::findOrCreate($p); }


        // Roles
        $admin = Role::findOrCreate('admin');
        $staff = Role::findOrCreate('staff');
        $customer = Role::findOrCreate('customer');


        // Grant
        $admin->givePermissionTo($perms);
        $staff->givePermissionTo(['manage courses','manage pages','manage files','send newsletters','view admin']);
    }
}
