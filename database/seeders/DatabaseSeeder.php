<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AnimeSeriesSeeder::class,
            RecipeSeeder::class,
            ExpandedRecipeSeeder::class,
        ]);

        User::query()->updateOrCreate(['email' => 'api@meshiverse.test'], [
            'name' => 'Integración MeshiVerse',
            'password' => 'password',
        ]);
    }
}
