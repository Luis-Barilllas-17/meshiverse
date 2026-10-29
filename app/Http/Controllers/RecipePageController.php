<?php

namespace App\Http\Controllers;

use App\Models\AnimeSeries;
use App\Models\Recipe;
use App\Support\DishOfTheDay;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RecipePageController extends Controller
{
    public function index(Request $request, DishOfTheDay $dishOfTheDay): View
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'anime' => ['nullable', 'string', 'max:100'],
            'difficulty' => ['nullable', 'in:fácil,media,desafiante'],
        ]);

        $series = AnimeSeries::query()
            ->withCount(['recipes' => fn (Builder $query): Builder => $query->published()])
            ->orderBy('sort_order')
            ->get();

        $featuredRecipes = Recipe::query()
            ->published()
            ->with('animeSeries')
            ->where('is_featured', true)
            ->latest('published_at')
            ->limit(3)
            ->get();

        $recipes = Recipe::query()
            ->published()
            ->with('animeSeries')
            ->filtered($filters['search'] ?? null, $filters['anime'] ?? null, $filters['difficulty'] ?? null)
            ->latest('published_at')
            ->paginate(9)
            ->withQueryString();

        return view('welcome', [
            'series' => $series,
            'featuredRecipes' => $featuredRecipes,
            'recipes' => $recipes,
            'filters' => $filters,
            'recipeCount' => Recipe::query()->published()->count(),
            'ingredientCount' => Recipe::query()->published()->get(['ingredients'])->sum(
                fn (Recipe $recipe): int => count($recipe->ingredients),
            ),
            'dishOfTheDay' => $dishOfTheDay->get(),
        ]);
    }

    public function show(Recipe $recipe): View
    {
        abort_unless($recipe->published_at?->isPast(), 404);

        $recipe->load('animeSeries');

        $relatedRecipes = Recipe::query()
            ->published()
            ->with('animeSeries')
            ->where('anime_series_id', $recipe->anime_series_id)
            ->whereKeyNot($recipe->getKey())
            ->limit(3)
            ->get();

        return view('recipes.show', compact('recipe', 'relatedRecipes'));
    }
}
