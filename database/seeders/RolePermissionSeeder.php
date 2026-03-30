<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Create permissions
        $permissions = [
            // NGO Management
            'ngo.create',
            'ngo.view',
            'ngo.edit',
            'ngo.delete',
            'ngo.verify',
            
            // Campaign Management
            'campaign.create',
            'campaign.view',
            'campaign.edit',
            'campaign.delete',
            'campaign.approve',
            
            // Donation Management
            'donation.view',
            'donation.process',
            'donation.refund',
            
            // User Management
            'user.create',
            'user.view',
            'user.edit',
            'user.delete',
            'user.assign-role',
            
            // Analytics
            'analytics.view',
            'analytics.export',
            
            // System Management
            'system.manage',
            'system.config',
            'audit.view',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles
        $roles = [
            'super_admin' => [
                'ngo.create', 'ngo.view', 'ngo.edit', 'ngo.delete', 'ngo.verify',
                'campaign.create', 'campaign.view', 'campaign.edit', 'campaign.delete', 'campaign.approve',
                'donation.view', 'donation.process', 'donation.refund',
                'user.create', 'user.view', 'user.edit', 'user.delete', 'user.assign-role',
                'analytics.view', 'analytics.export',
                'system.manage', 'system.config', 'audit.view'
            ],
            
            'state_admin' => [
                'ngo.view', 'ngo.verify',
                'campaign.view', 'campaign.approve',
                'donation.view',
                'user.view', 'user.assign-role',
                'analytics.view', 'analytics.export',
                'audit.view'
            ],
            
            'ngo_admin' => [
                'ngo.view', 'ngo.edit',
                'campaign.create', 'campaign.view', 'campaign.edit', 'campaign.delete',
                'donation.view',
                'user.view', 'user.create', 'user.edit'
            ],
            
            'ngo_staff' => [
                'ngo.view',
                'campaign.create', 'campaign.view', 'campaign.edit',
                'donation.view'
            ],

            'ngo_finance' => [
                'ngo.view',
                'donation.view',
                'user.view',
            ],
            
            'corporate_admin' => [
                'campaign.view',
                'donation.view',
                'analytics.view', 'analytics.export'
            ],
            
            'donor' => [
                'campaign.view',
                'donation.view'
            ],
            
            'vendor' => [
                'campaign.view'
            ],
            
            'volunteer' => [
                'campaign.view',
                'donation.view'
            ]
        ];

        foreach ($roles as $roleName => $rolePermissions) {
            $role = Role::firstOrCreate(['name' => $roleName]);
            $role->givePermissionTo($rolePermissions);
        }
    }
}
