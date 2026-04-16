<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cage>
 */
class CageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'farmer_id' => \App\Models\Farmer::factory(),
            'name' => fake()->randomElement(['Kandang A', 'Kandang B', 'Kandang C', 'Kandang D', 'Kolam 1', 'Kolam 2']),
            'location' => fake()->optional()->randomElement(['Sektor Utara', 'Sektor Selatan', 'Sektor Timur', 'Sektor Barat', 'Lantai 1', 'Lantai 2']),
            'capacity' => fake()->numberBetween(50, 500),
        ];
    }
}
