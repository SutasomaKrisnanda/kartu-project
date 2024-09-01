<?php

namespace Database\Factories;

use App\Models\CardEffect;
use App\Models\Item;
use Illuminate\Database\Eloquent\Factories\Factory;

class CardEffectFactory extends Factory
{
    protected $model = CardEffect::class;

    public function definition(): array
    {
        return [
            'item_id' => Item::factory()->state([
                'type' => 'card',
            ]),
            'effect_type' => $this->faker->randomElement(['attack', 'defense', 'heal', 'boost']),
            'effect_value' => $this->faker->numberBetween(1, 3),
            'cooldown' => $this->faker->numberBetween(1, 2),
        ];
    }
}
