<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FavoriteRecipeController extends Controller
{
    public function index(Request $request): View
    {
        $recipes = $request->user()->favoriteRecipes()
            ->with('animeSeries')
            ->latest('favorite_recipes.created_at')
            ->paginate(9);

        return view('user.favorites', compact('recipes'));
    }

    public function store(Request $request, Recipe $recipe): RedirectResponse
    {
        abort_unless($recipe->published_at?->isPast(), 404);

        $request->user()->favoriteRecipes()->syncWithoutDetaching([$recipe->getKey()]);

        return back()->with('success', 'La receta se agregó a tus favoritos.');
    }

    public function destroy(Request $request, Recipe $recipe): RedirectResponse
    {
        $request->user()->favoriteRecipes()->detach($recipe->getKey());

        return back()->with('success', 'La receta se quitó de tus favoritos.');
    }
}
