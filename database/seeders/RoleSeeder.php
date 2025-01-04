<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create(['name' => 'Super Admin']);
        $admin = Role::create(['name' => 'Admin']);
        $trainer = Role::create(['name' => 'trainer']);
        $member = Role::create(['name' => 'member']);
        $visitor = Role::create(['name' => 'visitor']);

        $admin->givePermissionTo([
            //permissions to trainers
            'view-trainer',
            'create-trainer',
            'edit-trainer',
            'delete-trainer',

            //permissions to training sessions
            'view-sessions',
            'create-sessions',
            'update-sessions',
            'delete-sessions',

            //permissions to equipments
            'view_equipment',
            //permissions to booking
            'book-session',
            'track-attendance',
            //permissions to membership-package
            'manage-membership-package',
            //permissions to membership-plan
            'manage-membership-plan',
            //permissions to members
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
            'view-schedule',
            'view-activities',
            'manage-activities',
            'view-attendance',
            'view-membership-stats',
            'view-package-distribution',
            'view-activities',
            'list-activities',
            'view_dashboard',
            'view_training_sessions',
            'view_equipment',
            'view_memberships'

        ]);

        $trainer->givePermissionTo([
            //permissions to training sessions
            'view-sessions',
            'create-sessions',
            'update-sessions',
            'delete-sessions',
            'track-attendance',
        ]);

        $member->givePermissionTo([
            'view-sessions',
            'book-session',
        ]);

    }
}
