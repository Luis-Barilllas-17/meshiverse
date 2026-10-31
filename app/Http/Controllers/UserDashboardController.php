<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class UserDashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $user = $request->user();
        abort_if($user?->isAdmin(), 403);

        return view('user.dashboard', [
            'favoriteRecipes' => $user->favoriteRecipes()->with('animeSeries')->latest('favorite_recipes.created_at')->limit(4)->get(),
            'suggestions' => $user->recipeSuggestions()->latest()->limit(5)->get(),
            'favoriteCount' => $user->favoriteRecipes()->count(),
            'suggestionCount' => $user->recipeSuggestions()->count(),
        ]);
    }
}
