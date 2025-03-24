<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleAndPermissionSeeder extends Seeder
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
            // Admin permissions
            'view all tickets',
            'manage users',
            'manage roles',
            'manage settings',
            
            // Agent permissions
            'view assigned tickets',
            'update ticket status',
            'add ticket comments',
            'close tickets',
            
            // Customer permissions
            'create tickets',
            'view own tickets',
            'close own tickets',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles and assign permissions
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->syncPermissions([
            'view all tickets',
            'manage users',
            'manage roles',
            'manage settings',
        ]);

        $agentRole = Role::firstOrCreate(['name' => 'agent']);
        $agentRole->syncPermissions([
            'view assigned tickets',
            'update ticket status',
            'add ticket comments',
            'close tickets',
        ]);

        $customerRole = Role::firstOrCreate(['name' => 'customer']);
        $customerRole->syncPermissions([
            'create tickets',
            'view own tickets',
            'add ticket comments',
            'close own tickets',
        ]);
    }
}
