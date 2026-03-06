<?php

namespace Database\Factories\Vettas;

use App\Models\User;
use App\Models\Vettas\VettasCategory;
use App\Models\Vettas\VettasPhoto;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Vettas\VettasPhoto>
 */
class VettasPhotoFactory extends Factory
{
    protected $model = VettasPhoto::class;

    public function definition(): array
    {
        return [
            'category_id' => VettasCategory::factory(),
            'title' => fake()->sentence(3),
            'caption' => fake()->sentence(),
            'description' => fake()->paragraph(),
            'image_path' => fake()->imageUrl(1280, 860, 'people', true),
            'alt_text' => fake()->sentence(4),
            'photographer_name' => fake()->name(),
            'location' => fake()->city(),
            'captured_at' => fake()->optional()->date(),
            'display_order' => fake()->numberBetween(0, 12),
            'is_featured' => false,
            'is_published' => true,
            'published_at' => now()->subDays(fake()->numberBetween(0, 20)),
            'created_by' => User::factory(),
            'updated_by' => null,
        ];
    }

    public function featured(): static
    {
        return $this->state(fn () => [
            'is_featured' => true,
        ]);
    }

    public function draft(): static
    {
        return $this->state(fn () => [
            'is_published' => false,
            'published_at' => null,
        ]);
    }
}
