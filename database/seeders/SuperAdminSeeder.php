<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seed or update the default Super Admin account
        User::updateOrCreate(
            ['email' => 'superadmin@example.com'],
            [
                'name' => 'Super Admin',
                'email' => 'superadmin@example.com',
                'phone' => '255700000001', // normalized format
                'password' => Hash::make('Password@123'),
                'role' => 'superadmin',
                'is_verified' => true,
                'phone_verified_at' => now(),
                'email_verified_at' => now(),
            ]
        );
    }
}
