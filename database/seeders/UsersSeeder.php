<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin User
        $admin = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('system@123'), // ⚠️ change this in production
                'user_password' => 'system@123',
                'email_verified_at' => Carbon::now(),
            ]
        );
        $admin->assignRole('admin');

        // Staff User
        $staff = User::firstOrCreate(
            ['email' => 'staff@gmail'],
            [
                'name' => 'Staff User',
                'password' => Hash::make('system@123'),
                'user_password' => 'system@123',
                'email_verified_at' => Carbon::now(),
            ]
        );
        $staff->assignRole('staff');

        // Customer User
        $customer = User::firstOrCreate(
            ['email' => 'customer@gmail.com'],
            [
                'name' => 'Customer User',
                'password' => Hash::make('system@123'),
                'user_password' => 'system@123',
                'email_verified_at' => Carbon::now(),
            ]
        );
        $customer->assignRole('customer');
    }
}
