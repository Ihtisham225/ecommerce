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
            ['email' => 'admin@infotechkw.co'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('Infotech@Q8-admin'), // ⚠️ change this in production
                'email_verified_at' => Carbon::now(),
            ]
        );
        $admin->assignRole('admin');

        // Staff User
        $staff = User::firstOrCreate(
            ['email' => 'support@infotechq8.com'],
            [
                'name' => 'Staff User',
                'password' => Hash::make('Infotech@Q8-admin'),
                'email_verified_at' => Carbon::now(),
            ]
        );
        $staff->assignRole('staff');

        // Customer User
        $customer = User::firstOrCreate(
            ['email' => 'uihtisham0@gmail.com'],
            [
                'name' => 'Customer User',
                'password' => Hash::make('customer-password'),
                'email_verified_at' => Carbon::now(),
            ]
        );
        $customer->assignRole('customer');
    }
}
