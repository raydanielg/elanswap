<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed Superadmin and Admin sample accounts
        // Phones normalized to 255XXXXXXXXX format for login
        $users = [
            [
                'name' => 'Super Admin',
                'email' => 'superadmin@example.com',
                'phone' => '255700000001',
                'password' => Hash::make('Password@123'),
                'role' => 'superadmin',
                'is_verified' => true,
                'phone_verified_at' => now(),
            ],
            [
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'phone' => '255700000002',
                'password' => Hash::make('Password@123'),
                'role' => 'admin',
                'is_verified' => true,
                'phone_verified_at' => now(),
            ],
        ];

        foreach ($users as $data) {
            User::updateOrCreate(
                ['email' => $data['email']],
                $data
            );
        }
    }
}
