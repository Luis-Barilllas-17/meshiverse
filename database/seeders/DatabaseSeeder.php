<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call([
            AnimeSeriesSeeder::class,
            RecipeSeeder::class,
            ExpandedRecipeSeeder::class,
        ]);

        User::query()->updateOrCreate(['email' => 'api@meshiverse.test'], [
            'name' => 'Integración MeshiVerse',
            'role' => User::ROLE_USER,
            'password' => 'password',
        ]);

        User::query()->updateOrCreate(['email' => 'admin@meshiverse.test'], [
            'name' => 'Administrador MeshiVerse',
            'role' => User::ROLE_ADMIN,
            'password' => 'password',
        ]);

        User::query()->updateOrCreate(['email' => 'usuario@meshiverse.test'], [
            'name' => 'Explorador del Calabozo',
            'role' => User::ROLE_USER,
            'password' => 'password',
        ]);
    }
}
