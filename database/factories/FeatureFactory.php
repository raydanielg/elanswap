<?php

namespace Database\Factories;

use App\Models\Feature;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Feature>
 */
class FeatureFactory extends Factory
{
    protected $model = Feature::class;

    public function definition(): array
    {
        $icons = ['bolt', 'shield', 'map', 'chart', 'users', 'clock'];
        return [
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->sentence(12),
            'icon' => $this->faker->randomElement($icons),
            'sort_order' => $this->faker->numberBetween(0, 100),
            'is_active' => true,
        ];
    }
}
