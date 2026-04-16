<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\EggProduction>
 */
class EggProductionFactory extends Factory
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
            'quantity' => fake()->numberBetween(5, 50),
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
