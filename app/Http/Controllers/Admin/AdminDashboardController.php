<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Recipe;
use App\Models\RecipeSuggestion;
use App\Models\User;
use App\Support\DishOfTheDay;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function __invoke(DishOfTheDay $dishOfTheDay): View
    {
        return view('admin.dashboard', [
            'recipeCount' => Recipe::query()->count(),
            'publishedRecipeCount' => Recipe::query()->published()->count(),
            'userCount' => User::query()->where('role', User::ROLE_USER)->count(),
            'suggestionCount' => RecipeSuggestion::query()->where('status', 'pending')->count(),
            'favoriteCount' => DB::table('favorite_recipes')->count(),
            'dishOfTheDay' => $dishOfTheDay->get(),
            'latestRecipes' => Recipe::query()->with('animeSeries')->latest()->limit(5)->get(),
        ]);
    }
}
