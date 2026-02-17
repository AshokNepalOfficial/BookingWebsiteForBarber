<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Create Permissions
        $permissions = [
            // Dashboard
            ['name' => 'view_dashboard', 'display_name' => 'View Dashboard', 'group' => 'dashboard', 'description' => 'Access to admin dashboard'],
            
            // Bookings
            ['name' => 'view_bookings', 'display_name' => 'View Bookings', 'group' => 'bookings', 'description' => 'View all bookings'],
            ['name' => 'create_bookings', 'display_name' => 'Create Bookings', 'group' => 'bookings', 'description' => 'Create new bookings'],
            ['name' => 'edit_bookings', 'display_name' => 'Edit Bookings', 'group' => 'bookings', 'description' => 'Edit existing bookings'],
            ['name' => 'delete_bookings', 'display_name' => 'Delete Bookings', 'group' => 'bookings', 'description' => 'Delete bookings'],
            ['name' => 'confirm_bookings', 'display_name' => 'Confirm Bookings', 'group' => 'bookings', 'description' => 'Confirm pending bookings'],
            ['name' => 'complete_bookings', 'display_name' => 'Complete Bookings', 'group' => 'bookings', 'description' => 'Mark bookings as completed'],
            
            // Customers
            ['name' => 'view_customers', 'display_name' => 'View Customers', 'group' => 'customers', 'description' => 'View customer list'],
            ['name' => 'create_customers', 'display_name' => 'Create Customers', 'group' => 'customers', 'description' => 'Add new customers'],
            ['name' => 'edit_customers', 'display_name' => 'Edit Customers', 'group' => 'customers', 'description' => 'Edit customer information'],
            ['name' => 'delete_customers', 'display_name' => 'Delete Customers', 'group' => 'customers', 'description' => 'Delete customers'],
            ['name' => 'manage_loyalty', 'display_name' => 'Manage Loyalty Points', 'group' => 'customers', 'description' => 'Adjust customer loyalty points'],
            
            // Services
            ['name' => 'view_services', 'display_name' => 'View Services', 'group' => 'services', 'description' => 'View service list'],
            ['name' => 'create_services', 'display_name' => 'Create Services', 'group' => 'services', 'description' => 'Add new services'],
            ['name' => 'edit_services', 'display_name' => 'Edit Services', 'group' => 'services', 'description' => 'Edit service details'],
            ['name' => 'delete_services', 'display_name' => 'Delete Services', 'group' => 'services', 'description' => 'Delete services'],
            
            // Memberships
            ['name' => 'view_memberships', 'display_name' => 'View Memberships', 'group' => 'memberships', 'description' => 'View membership plans'],
            ['name' => 'create_memberships', 'display_name' => 'Create Memberships', 'group' => 'memberships', 'description' => 'Create membership plans'],
            ['name' => 'edit_memberships', 'display_name' => 'Edit Memberships', 'group' => 'memberships', 'description' => 'Edit membership plans'],
            ['name' => 'delete_memberships', 'display_name' => 'Delete Memberships', 'group' => 'memberships', 'description' => 'Delete membership plans'],
            
            // Transactions
            ['name' => 'view_transactions', 'display_name' => 'View Transactions', 'group' => 'transactions', 'description' => 'View payment transactions'],
            ['name' => 'create_transactions', 'display_name' => 'Create Transactions', 'group' => 'transactions', 'description' => 'Record new transactions'],
            ['name' => 'verify_payments', 'display_name' => 'Verify Payments', 'group' => 'transactions', 'description' => 'Verify offline payments'],
            
            // Staff
            ['name' => 'view_staff', 'display_name' => 'View Staff', 'group' => 'staff', 'description' => 'View staff members'],
            ['name' => 'create_staff', 'display_name' => 'Create Staff', 'group' => 'staff', 'description' => 'Add new staff members'],
            ['name' => 'edit_staff', 'display_name' => 'Edit Staff', 'group' => 'staff', 'description' => 'Edit staff information'],
            ['name' => 'delete_staff', 'display_name' => 'Delete Staff', 'group' => 'staff', 'description' => 'Remove staff members'],
            
            // Roles & Permissions
            ['name' => 'view_roles', 'display_name' => 'View Roles', 'group' => 'roles', 'description' => 'View role list'],
            ['name' => 'create_roles', 'display_name' => 'Create Roles', 'group' => 'roles', 'description' => 'Create new roles'],
            ['name' => 'edit_roles', 'display_name' => 'Edit Roles', 'group' => 'roles', 'description' => 'Edit roles and assign permissions'],
            ['name' => 'delete_roles', 'display_name' => 'Delete Roles', 'group' => 'roles', 'description' => 'Delete roles'],
            
            // Settings & CMS
            ['name' => 'view_settings', 'display_name' => 'View Settings', 'group' => 'settings', 'description' => 'View system settings'],
            ['name' => 'edit_settings', 'display_name' => 'Edit Settings', 'group' => 'settings', 'description' => 'Modify system settings'],
            ['name' => 'manage_cms', 'display_name' => 'Manage CMS', 'group' => 'cms', 'description' => 'Manage website content'],
            ['name' => 'manage_testimonials', 'display_name' => 'Manage Testimonials', 'group' => 'cms', 'description' => 'Manage customer testimonials'],
            
            // Reports
            ['name' => 'view_reports', 'display_name' => 'View Reports', 'group' => 'reports', 'description' => 'Access reports and analytics'],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission['name']],
                $permission
            );
        }

        // Create Roles
        $adminRole = Role::firstOrCreate(
            ['name' => 'admin'],
            [
                'display_name' => 'Administrator',
                'description' => 'Full system access with all permissions',
                'is_active' => true,
            ]
        );

        $managerRole = Role::firstOrCreate(
            ['name' => 'manager'],
            [
                'display_name' => 'Manager',
                'description' => 'Manages staff, bookings, and operations',
                'is_active' => true,
            ]
        );

        $receptionistRole = Role::firstOrCreate(
            ['name' => 'receptionist'],
            [
                'display_name' => 'Receptionist',
                'description' => 'Handles bookings and customer management',
                'is_active' => true,
            ]
        );

        $barberRole = Role::firstOrCreate(
            ['name' => 'barber'],
            [
                'display_name' => 'Barber',
                'description' => 'Service provider with limited access',
                'is_active' => true,
            ]
        );

        // Assign Permissions to Admin (All permissions)
        $adminRole->permissions()->sync(Permission::all());

        // Assign Permissions to Manager
        $managerPermissions = Permission::whereIn('name', [
            'view_dashboard',
            'view_bookings', 'create_bookings', 'edit_bookings', 'confirm_bookings', 'complete_bookings',
            'view_customers', 'create_customers', 'edit_customers', 'manage_loyalty',
            'view_services', 'create_services', 'edit_services',
            'view_memberships', 'create_memberships', 'edit_memberships',
            'view_transactions', 'create_transactions', 'verify_payments',
            'view_staff', 'create_staff', 'edit_staff',
            'view_reports',
            'manage_testimonials',
        ])->get();
        $managerRole->permissions()->sync($managerPermissions);

        // Assign Permissions to Receptionist
        $receptionistPermissions = Permission::whereIn('name', [
            'view_dashboard',
            'view_bookings', 'create_bookings', 'edit_bookings', 'confirm_bookings',
            'view_customers', 'create_customers', 'edit_customers',
            'view_services',
            'view_memberships',
            'view_transactions', 'create_transactions',
        ])->get();
        $receptionistRole->permissions()->sync($receptionistPermissions);

        // Assign Permissions to Barber
        $barberPermissions = Permission::whereIn('name', [
            'view_dashboard',
            'view_bookings',
            'complete_bookings',
        ])->get();
        $barberRole->permissions()->sync($barberPermissions);

        $this->command->info('Roles and permissions seeded successfully!');
    }
}
