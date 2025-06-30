<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolesAndAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create roles
        Role::firstOrCreate(['name' => 'Admin']);
        Role::firstOrCreate(['name' => 'Staff']);
        Role::firstOrCreate(['name' => 'Customer']);

        // Create initial admin user (in admins table)
        $admin = Admin::firstOrCreate(
            ['email' => 'admin@awlad-elewa.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password123'),
            ]
        );

        // Optionally, create a user with Admin role in users table
        $user = User::firstOrCreate(
            ['email' => 'adminuser@awlad-elewa.com'],
            [
                'name' => 'Admin User',
                'phone1' => '01000000000',
                'phone2' => null,
                'password' => Hash::make('password123'),
            ]
        );
        $user->assignRole('Admin');
    }
}
