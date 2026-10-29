<?php

namespace Database\Seeders;

use App\Models\AnimeSeries;
use Illuminate\Database\Seeder;

class AnimeSeriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AnimeSeries::query()->updateOrCreate(['slug' => 'one-piece'], [
            'name' => 'One Piece',
            'tagline' => 'El recetario de Sanji',
            'description' => 'Platos de alta mar, banquetes piratas y recetas capaces de alimentar a toda una tripulación.',
            'color_from' => '#ff6b35',
            'color_to' => '#f6c453',
            'icon' => '☠',
            'image_path' => 'images/series/one-piece.jpg',
            'sort_order' => 1,
        ]);

        AnimeSeries::query()->updateOrCreate(['slug' => 'delicious-in-dungeon'], [
            'name' => 'Delicious in Dungeon',
            'tagline' => 'Cocina fantástica del calabozo',
            'description' => 'Recetas de supervivencia, monstruos sorprendentes y la creatividad culinaria de Senshi.',
            'color_from' => '#38b997',
            'color_to' => '#8d6bd8',
            'icon' => '⚔',
            'image_path' => 'images/series/delicious-in-dungeon.jpg',
            'sort_order' => 2,
        ]);

        AnimeSeries::query()->updateOrCreate(['slug' => 'dr-stone'], [
            'name' => 'Dr. Stone',
            'tagline' => 'Cocina científica del Reino de la Ciencia',
            'description' => 'Ramen, refrescos y dulces reconstruidos desde cero con ciencia, ingenio y recursos del mundo de piedra.',
            'color_from' => '#84cc16',
            'color_to' => '#22d3ee',
            'icon' => '🧪',
            'image_path' => 'images/series/dr-stone.jpg',
            'sort_order' => 3,
        ]);

        AnimeSeries::query()->updateOrCreate(['slug' => 'todays-menu-for-emiya-family'], [
            'name' => "Today's Menu for the Emiya Family",
            'tagline' => 'La cocina cotidiana de Shirou Emiya',
            'description' => 'Comida japonesa reconfortante, técnica casera y cenas compartidas por la familia Emiya.',
            'color_from' => '#60a5fa',
            'color_to' => '#f9a8d4',
            'icon' => '🍱',
            'image_path' => 'images/series/emiya-family.jpg',
            'sort_order' => 4,
        ]);

        AnimeSeries::query()->updateOrCreate(['slug' => 'food-wars'], [
            'name' => 'Food Wars!',
            'tagline' => 'Duelo culinario en Tōtsuki',
            'description' => 'Platos creativos, técnicas atrevidas y recetas dignas de un shokugeki para cocineros que aceptan el reto.',
            'color_from' => '#fb7185',
            'color_to' => '#fbbf24',
            'icon' => '🔥',
            'image_path' => 'images/series/food-wars.jpg',
            'sort_order' => 5,
        ]);

        AnimeSeries::query()->updateOrCreate(['slug' => 'monster-hunter'], [
            'name' => 'Monster Hunter',
            'tagline' => 'Banquetes antes de la cacería',
            'description' => 'Grandes parrilladas de World y Wilds, guisos de Iceborne y los famosos dango de Kamura en Rise.',
            'color_from' => '#f59e0b',
            'color_to' => '#65a30d',
            'icon' => '🐾',
            'image_path' => 'images/series/monster-hunter.webp',
            'sort_order' => 6,
        ]);
    }
}
