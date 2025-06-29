<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Create sample driver
        User::create([
            'name' => 'John Driver',
            'email' => 'driver@example.com',
            'password' => Hash::make('password'),
            'role' => 'driver',
        ]);

        // Create sample passenger
        User::create([
            'name' => 'Jane Passenger',
            'email' => 'passenger@example.com',
            'password' => Hash::make('password'),
            'role' => 'passenger',
        ]);
    }
} 