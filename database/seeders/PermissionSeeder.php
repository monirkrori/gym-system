<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'manage-subscriptions',
            'create-role',
            'edit-role',
            'delete-role',
            'view-trainer',
            'create-trainer',
            'edit-trainer',
            'delete-trainer',
            'view-sessions',
            'create-sessions',
            'update-sessions',
            'delete-sessions',
            'manage-equipment',
            'book-session',
            'track-attendance',
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
            'view_members',
            'view_training_sessions',
            'view_equipment',
            'view_memberships',
            'export-memberships-pdf',
            'export-memberships-excel',
            'export-memberships',
            'delete-membership',
            'edit-membership',
            'view-membership-statistics',
            'create-membership',
            'view-membership'

        ];

        // Looping and Inserting Array's Permissions into Permission Table
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }
}
