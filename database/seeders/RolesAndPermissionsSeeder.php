<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            'manage hotels',
            'manage reservations',
            'manage guests',
            'manage invoices',
            'manage operations',
            'view reports',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles and assign permissions
        $admin = Role::create(['name' => 'admin']);
        $admin->givePermissionTo(Permission::all());

        $manager = Role::create(['name' => 'manager']);
        $manager->givePermissionTo([
            'manage reservations',
            'manage guests',
            'manage invoices',
            'manage operations',
            'view reports',
        ]);

        $receptionist = Role::create(['name' => 'receptionist']);
        $receptionist->givePermissionTo([
            'manage reservations',
            'manage guests',
            'manage invoices',
        ]);

        $housekeeping = Role::create(['name' => 'housekeeping']);
        $housekeeping->givePermissionTo([
            'manage operations',
        ]);
    }
}
