<?php

namespace Database\Factories;

use App\Models\AnimeSeries;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<AnimeSeries>
 */
class AnimeSeriesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->words(3, true);

        return [
            'name' => Str::title($name),
            'slug' => Str::slug($name),
            'tagline' => fake()->sentence(6),
            'description' => fake()->paragraph(),
            'color_from' => fake()->hexColor(),
            'color_to' => fake()->hexColor(),
            'icon' => '✦',
            'image_path' => 'images/series/one-piece.jpg',
            'sort_order' => fake()->numberBetween(1, 20),
        ];
    }
}
