<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed Admin User
        User::create([
            'name' => 'BookMyLPG Admin',
            'mobile_number' => '9876543210',
            'password' => Hash::make('admin123'),
            'is_admin' => true,
        ]);

        // Seed a standard customer User for API testing
        User::create([
            'name' => 'Demo Customer',
            'mobile_number' => '8765432109',
            'password' => Hash::make('password123'),
            'is_admin' => false,
        ]);

        // Seed a Deliveryman for API & login testing
        \App\Models\Deliveryman::create([
            'name' => 'Demo Deliveryman',
            'mobile_number' => '9876543222',
            'password' => Hash::make('password123'),
        ]);
    }
}
