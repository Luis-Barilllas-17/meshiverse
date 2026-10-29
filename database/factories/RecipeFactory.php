<?php

namespace Database\Factories;

use App\Models\AnimeSeries;
use App\Models\Recipe;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Recipe>
 */
class RecipeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->unique()->words(4, true);

        return [
            'anime_series_id' => AnimeSeries::factory(),
            'title' => Str::title($title),
            'slug' => Str::slug($title),
            'subtitle' => fake()->sentence(5),
            'description' => fake()->paragraph(),
            'image_path' => 'images/recipes/1ee7c621-9580-495d-a689-460d093c52d1.jpg',
            'source_label' => 'Receta inspirada en anime',
            'ingredients' => ['2 tazas de arroz', '1 cebolla', 'Sal y pimienta al gusto'],
            'steps' => ['Prepara todos los ingredientes.', 'Cocina a fuego medio hasta dorar.', 'Sirve caliente.'],
            'servings' => fake()->numberBetween(2, 6),
            'prep_minutes' => fake()->numberBetween(10, 30),
            'cook_minutes' => fake()->numberBetween(15, 60),
            'difficulty' => fake()->randomElement(['fácil', 'media', 'desafiante']),
            'is_featured' => false,
            'published_at' => now(),
        ];
    }

    public function featured(): static
    {
        return $this->state(fn (): array => ['is_featured' => true]);
    }

    public function draft(): static
    {
        return $this->state(fn (): array => ['published_at' => null]);
    }
}
