<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FeedRecord>
 */
class FeedRecordFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'animal_id' => \App\Models\Animal::factory(),
            'date' => fake()->dateTimeBetween('-1 year', 'now'),
            'feed_type' => fake()->randomElement(['Pakan Starter', 'Pakan Grower', 'Pakan Layer', 'Pakan Konsentrat', 'Bekatul', 'Jagung']),
            'quantity' => fake()->randomFloat(2, 0.5, 50),
            'unit' => fake()->randomElement(['kg', 'gram', 'liter']),
        ];
    }
}
