<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Log;
use App\Models\User;
use Illuminate\Support\Carbon;

class LogSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure there are users
        if (User::count() === 0) {
            User::factory()->count(3)->create();
        }

        // Seed general recent activities
        Log::factory()->count(30)->create();

        // Seed specific login logs for each user to show in Last Login History
        foreach (User::all() as $user) {
            Log::factory()
                ->login()
                ->count(5)
                ->create([
                    'user_id' => $user->id,
                    'phone' => $user->phone,
                    // spread over last 14 days
                    'record_date' => fake()->dateTimeBetween('-14 days', 'now')->format('Y-m-d'),
                ]);
        }
    }
}
