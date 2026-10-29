<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRecipeRequest;
use App\Http\Requests\UpdateRecipeRequest;
use App\Http\Resources\RecipeResource;
use App\Models\Recipe;
use App\Support\DishOfTheDay;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Str;

class RecipeController extends Controller
{
    public function dishOfTheDay(DishOfTheDay $dishOfTheDay): JsonResponse
    {
        $recipe = $dishOfTheDay->get();

        abort_if($recipe === null, 404, 'No hay recetas publicadas.');

        return (new RecipeResource($recipe))
            ->additional(['meta' => ['date' => now()->toDateString(), 'selection' => 'dish-of-the-day']])
            ->response();
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'anime' => ['nullable', 'string', 'max:100'],
            'difficulty' => ['nullable', 'in:fácil,media,desafiante'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:50'],
        ]);

        $recipes = Recipe::query()
            ->published()
            ->with('animeSeries')
            ->filtered($filters['search'] ?? null, $filters['anime'] ?? null, $filters['difficulty'] ?? null)
            ->latest('published_at')
            ->paginate($filters['per_page'] ?? 12)
            ->withQueryString();

        return RecipeResource::collection($recipes);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRecipeRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['slug'] = $this->uniqueSlug($data['slug'] ?? $data['title']);

        if (! array_key_exists('published_at', $data)) {
            $data['published_at'] = now();
        }

        $recipe = Recipe::query()->create($data)->load('animeSeries');

        return (new RecipeResource($recipe))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Recipe $recipe): RecipeResource
    {
        abort_unless($recipe->published_at?->isPast(), 404);

        return new RecipeResource($recipe->load('animeSeries'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRecipeRequest $request, Recipe $recipe): RecipeResource
    {
        $data = $request->validated();

        if (array_key_exists('slug', $data)) {
            $data['slug'] = $this->uniqueSlug($data['slug'] ?: ($data['title'] ?? $recipe->title), $recipe);
        }

        $recipe->update($data);

        return new RecipeResource($recipe->refresh()->load('animeSeries'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Recipe $recipe): JsonResponse
    {
        $recipe->delete();

        return response()->json(status: 204);
    }

    private function uniqueSlug(string $value, ?Recipe $ignoredRecipe = null): string
    {
        $baseSlug = Str::slug($value) ?: 'receta';
        $slug = $baseSlug;
        $suffix = 2;

        while (Recipe::query()
            ->when($ignoredRecipe, fn ($query) => $query->whereKeyNot($ignoredRecipe->getKey()))
            ->where('slug', $slug)
            ->exists()) {
            $slug = "{$baseSlug}-{$suffix}";
            $suffix++;
        }

        return $slug;
    }
}
