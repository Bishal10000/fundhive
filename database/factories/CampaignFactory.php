<?php

namespace Database\Factories;

use App\Models\Campaign;
use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class CampaignFactory extends Factory
{
    protected $model = Campaign::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'category_id' => Category::factory(),
            'title' => $this->faker->sentence,
            'slug' => $this->faker->unique()->slug,
            'description' => $this->faker->paragraph,
            'story' => $this->faker->paragraph(4),
            'goal_amount' => $this->faker->numberBetween(10000, 100000),
            'current_amount' => $this->faker->numberBetween(0, 50000),
            'deadline' => now()->addDays(30),
            'featured_image' => 'default.jpg',
            'gallery_images' => json_encode([]),
            'video_url' => null,
            'status' => 'completed',
            'is_featured' => false,
            'is_verified' => true,
            'views' => rand(0, 500),
            'fraud_score' => rand(0, 100),
            'is_flagged' => rand(0, 1),
        ];
    }
}
