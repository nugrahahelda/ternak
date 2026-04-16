<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // Ternak permissions
            'view_ternak',
            'create_ternak',
            'edit_ternak',
            'delete_ternak',

            // Laporan permissions
            'view_laporan',
            'create_laporan',
            'edit_laporan',
            'delete_laporan',

            // Monitoring permissions
            'view_monitoring',
            'create_monitoring',
            'edit_monitoring',
            'delete_monitoring',

            // User management permissions
            'view_users',
            'create_users',
            'edit_users',
            'delete_users',

            // Role & Permission management
            'view_roles',
            'create_roles',
            'edit_roles',
            'delete_roles',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles and assign permissions

        // Admin role - full access
        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all());

        // Peternak role - akses data ternak & laporan
        $peternakRole = Role::create(['name' => 'peternak']);
        $peternakRole->givePermissionTo([
            'view_ternak',
            'create_ternak',
            'edit_ternak',
            'view_laporan',
            'create_laporan',
        ]);

        // Petugas role - akses monitoring & laporan
        $petugasRole = Role::create(['name' => 'petugas']);
        $petugasRole->givePermissionTo([
            'view_monitoring',
            'create_monitoring',
            'edit_monitoring',
            'view_laporan',
            'create_laporan',
        ]);
    }
}
