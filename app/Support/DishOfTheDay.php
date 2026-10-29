<?php

namespace App\Support;

use App\Models\Recipe;
use Carbon\CarbonInterface;

class DishOfTheDay
{
    public function get(?CarbonInterface $date = null): ?Recipe
    {
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
