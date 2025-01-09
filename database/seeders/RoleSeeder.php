<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create(['name' => 'Super admin']);
        $admin = Role::create(['name' => 'admin']);
        $trainer = Role::create(['name' => 'trainer']);
        $member = Role::create(['name' => 'member']);

        // Admin permissions (all-inclusive)
        $admin->givePermissionTo([
            'view-trainer',
            'create-trainer',
            'edit-trainer',
            'delete-trainer',
            'view-sessions',
            'create-sessions',
            'update-sessions',
            'delete-sessions',
            'book-session',
            'track-attendance',
            'manage-membership-package',
            'manage-membership-plan',
            'view-membership',
            'create-membership',
            'edit-membership',
            'delete-membership',
            'view-revenue',
            'view-reports',
            'generate-reports',
            'manage-meal-plans',
            'view-meal-plans',
            'subscribe-meal-plans',
            'view-statistics',
            'view-schedule',
            'view-activities',
            'manage-activities',
            'view-attendance',
            'view-membership-stats',
            'view-package-distribution',
            'list-activities',
            'view_dashboard',
            'view_training_sessions',
            'view_memberships',
        ]);

        // Trainer permissions
        $trainer->givePermissionTo([
            'view-trainer',
            'view-sessions',
            'create-sessions',
            'update-sessions',
            'delete-sessions',
            'track-attendance',
            'view-schedule',
            'view-activities',
            'manage-activities',
        ]);

        // Member permissions
        $member->givePermissionTo([
            'view-sessions',
            'book-session',
            'view-trainer',
            'view-meal-plans',
            'subscribe-meal-plans',
            'view-statistics',
            'view-schedule',
        ]);

    }
}
