<?php

namespace App\Support;

use App\Models\DishOfTheDaySetting;
use App\Models\Recipe;
use Carbon\CarbonInterface;

class DishOfTheDay
{
    public function get(?CarbonInterface $date = null): ?Recipe
    {
        $setting = DishOfTheDaySetting::query()->first();

        if ($setting?->mode === 'manual' && $setting->recipe_id !== null) {
            $manualRecipe = Recipe::query()
                ->published()
                ->with('animeSeries')
                ->find($setting->recipe_id);

            if ($manualRecipe !== null) {
                return $manualRecipe;
            }
        }

        $recipeIds = Recipe::query()->published()->orderBy('id')->pluck('id');

        if ($recipeIds->isEmpty()) {
            return null;
        }

        $date ??= now();
        $hash = (int) sprintf('%u', crc32($date->toDateString()));
        $recipeId = $recipeIds[$hash % $recipeIds->count()];

        return Recipe::query()->with('animeSeries')->find($recipeId);
    }
}
