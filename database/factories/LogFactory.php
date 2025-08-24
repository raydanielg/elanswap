<?php

namespace Database\Factories;

use App\Models\Log;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/** @extends Factory<\App\Models\Log> */
class LogFactory extends Factory
{
    protected $model = Log::class;

    public function definition(): array
    {
        // pick a user or null
        $user = User::inRandomOrder()->first();

        $types = ['login', 'activity', 'profile_update', 'password_change'];
        $logType = $this->faker->randomElement($types);

        return [
            'user_id' => $user?->id,
            'record_date' => $this->faker->dateTimeBetween('-30 days', 'now')->format('Y-m-d'),
            'phone' => $user?->phone ?? ('2557' . $this->faker->randomNumber(8, true)),
            'text' => match ($logType) {
                'login' => 'User logged in successfully',
                'profile_update' => 'User updated profile information',
                'password_change' => 'User changed password',
                default => Str::ucfirst($this->faker->words(5, true)),
            },
            'status' => $this->faker->randomElement(['pending', 'sent', 'failed']),
            'log_type' => $logType,
            'ip_address' => $this->faker->ipv4(),
            'user_agent' => $this->faker->userAgent(),
        ];
    }

    public function login(): static
    {
        return $this->state(fn () => [
            'log_type' => 'login',
            'text' => 'User logged in successfully',
            'status' => 'sent',
        ]);
    }
}
