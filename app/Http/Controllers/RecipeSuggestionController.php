<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRecipeSuggestionRequest;
use App\Models\RecipeSuggestion;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class RecipeSuggestionController extends Controller
{
    public function create(): View
    {
        return view('suggestions.create');
    }

    public function store(StoreRecipeSuggestionRequest $request): RedirectResponse
    {
        RecipeSuggestion::query()->create($request->validated());

        return to_route('suggestions.create')->with('success', '¡Tu propuesta entró a la lista de futuras aventuras culinarias!');
    }
}
