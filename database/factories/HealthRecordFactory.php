<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\HealthRecord>
 */
class HealthRecordFactory extends Factory
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
            'type' => fake()->randomElement(['vaksin', 'sakit', 'pengobatan']),
            'description' => fake()->sentence(10),
        ];
    }
}
