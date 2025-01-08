<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Creating Super admin User
        $superAdmin = User::create([
            'name' => 'moner kfori',
            'email' => 'monirkfori7@gmail.com',
            'password' => Hash::make('monir12345')
        ]);
        $superAdmin->assignRole('Super admin');

        // Creating admin User
        $admin = User::create([
            'name' => 'hala hasan',
            'email' => 'halahasan@gmail.com',
            'password' => Hash::make('hala12345')
        ]);
        $admin->assignRole('admin');

        // Creating trainer
        $trainer = User::create([
            'name' => 'fatima saleh',
            'email' => 'fatimasaleh@gmail.com',
            'password' => Hash::make('fatima12345')
        ]);
        $trainer->assignRole('trainer');

        // Creating member
        $member = User::create([
            'name' => 'hadeel hanna',
            'email' => 'hadeelhanna@gmail.com',
            'password' => Hash::make('hadeel12345')
        ]);
        $trainer->assignRole('trainer');
    }

}
