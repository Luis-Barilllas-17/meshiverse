<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAdminRecipeRequest;
use App\Models\AnimeSeries;
use App\Models\Recipe;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminRecipeController extends Controller
{
    public function create(): View
    {
        return view('admin.recipes.create', [
            'series' => AnimeSeries::query()->orderBy('sort_order')->get(),
        ]);
    }

    public function store(StoreAdminRecipeRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $storedImage = $request->file('image')->store('recipes/admin', 'public');
        $slug = ($data['slug'] ?? null) ?: Str::slug($data['title']);

        while (Recipe::query()->where('slug', $slug)->exists()) {
            $slug = Str::slug($data['title']).'-'.Str::lower(Str::random(5));
        }

        $data['slug'] = $slug;
        $data['image_path'] = 'storage/'.$storedImage;
        $data['is_featured'] = $request->boolean('is_featured');
        $data['published_at'] = $request->boolean('publish_now') ? now() : null;

        unset($data['image'], $data['publish_now']);

        $recipe = Recipe::query()->create($data);

        return to_route('admin.recipes.create')
            ->with('success', "La receta {$recipe->title} fue creada correctamente.");
    }
}
