<?php

namespace Database\Factories;

use App\Models\Quest;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\QuestProgress>
 */
class QuestProgressFactory extends Factory
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
            'quest_id' => Quest::factory(), // Removed $quest variable
            'current_progress' => fake()->numberBetween(0, 10), // Use a fixed number or pass required_progress as parameter
            'is_completed' => false,
        ];
    }

    public function forQuest(Quest $quest): Factory
    {
        return $this->state(function (array $attributes) use ($quest) {
            return [
                'quest_id' => $quest->id,
                'current_progress' => fake()->numberBetween(0, $quest->required_progress),
            ];
        });
    }
}
