<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create module-based permissions
        $permissions = [
            'user-management.read',
            'user-management.add',
            'user-management.edit',
            'user-management.delete',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // create roles
        $roleSuperAdmin = Role::firstOrCreate(['name' => 'super admin']);
        $roleAdmin = Role::firstOrCreate(['name' => 'admin']);
        $roleUser = Role::firstOrCreate(['name' => 'user']);

        // Admin gets all module permissions by default but misses super-admin privileges
        $roleAdmin->givePermissionTo(Permission::all());

        // Basic user role gets assigned read
        $roleUser->givePermissionTo(['user-management.read']);

        // Super Admin intrinsically bypasses these due to the Gate, but we can assign all for completeness
        $roleSuperAdmin->givePermissionTo(Permission::all());

        // Create a default super admin user
        $superAdminUser = User::firstOrCreate([
            'email' => 'admin@admin.com',
        ], [
            'name' => 'Super Admin',
            'password' => bcrypt('password'),
        ]);
        $superAdminUser->assignRole($roleSuperAdmin);

        // Create a default standard admin user for testing
        $adminUser = User::firstOrCreate([
            'email' => 'admin2@admin.com',
        ], [
            'name' => 'Standard Admin',
            'password' => bcrypt('password'),
        ]);
        $adminUser->assignRole($roleAdmin);
    }
}
