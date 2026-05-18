<?php

namespace Database\Factories;

use App\Models\Material;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Material>
 */
class MaterialFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'title' => fake()->sentence(3),
            'source_name' => fake()->word().'.pdf',
            'original_path' => 'uploads/'.fake()->uuid(),
            'summary' => fake()->paragraphs(3, true),
            'concepts' => [
                ['title' => fake()->word(), 'short_explanation' => fake()->sentence()],
                ['title' => fake()->word(), 'short_explanation' => fake()->sentence()],
            ],
            'status' => 'completed',
        ];
    }
}
