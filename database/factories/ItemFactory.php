<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Item>
 */
class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->sentence(2),
            'token' => fake()->slug(2),
            'element' => fake()->randomElement(['Fire', 'Water', 'Earth', 'Air']),
            'rarity' => fake()->numberBetween(1, 10),
            'description' => fake()->text(200),
            'image' => fake()->imageUrl(640, 1024, true, 'animals'),
            'type' => fake()->randomElement(['card', 'item', 'material']),
        ];
    }
}
