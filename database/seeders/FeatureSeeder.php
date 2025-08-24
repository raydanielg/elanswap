<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Feature;

class FeatureSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'title' => 'Smart matching',
                'description' => 'Automatically match employees to stations based on region, rank, and preference.',
                'icon' => 'bolt',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Secure & transparent',
                'description' => 'Audit trails and clear steps for every exchange to build trust.',
                'icon' => 'shield',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'Geo preferences',
                'description' => 'Filter and match using preferred regions and destinations.',
                'icon' => 'map',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'title' => 'Insights & reports',
                'description' => 'Quick stats to support decision making for admins.',
                'icon' => 'chart',
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'title' => 'Collaboration',
                'description' => 'Coordinate exchanges efficiently between users and offices.',
                'icon' => 'users',
                'sort_order' => 5,
                'is_active' => true,
            ],
            [
                'title' => '24/7 access',
                'description' => 'Access the system anytime and move faster.',
                'icon' => 'clock',
                'sort_order' => 6,
                'is_active' => true,
            ],
        ];

        foreach ($data as $row) {
            Feature::updateOrCreate(['title' => $row['title']], $row);
        }
    }
}
