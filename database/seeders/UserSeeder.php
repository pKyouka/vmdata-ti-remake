<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin
        User::firstOrCreate(
            ['email' => env('ADMIN_EMAIL', 'admin@example.com')],
            [
                'name' => env('ADMIN_NAME', 'Admin'),
                'password' => env('ADMIN_PASSWORD', 'admin123'), // Cast 'hashed' in Model handle this
                'role' => 'admin',
                'is_verified' => true,
                'phone' => '08123456789',
                'organization' => 'IT Department',
            ]
        );

        // User Demo
        User::firstOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'User Demo',
                'password' => 'user123', // Cast 'hashed' in Model handle this
                'role' => 'user',
                'is_verified' => true,
                'phone' => '08987654321',
                'organization' => 'Guest Corp',
            ]
        );
    }
}
