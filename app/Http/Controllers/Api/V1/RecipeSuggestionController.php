<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRecipeSuggestionRequest;
use App\Models\RecipeSuggestion;
use Illuminate\Http\JsonResponse;

class RecipeSuggestionController extends Controller
{
    public function store(StoreRecipeSuggestionRequest $request): JsonResponse
    {
        $suggestion = RecipeSuggestion::query()->create($request->validated());

        return response()->json([
            'message' => 'Sugerencia recibida. Gracias por ampliar el recetario.',
            'data' => [
                'id' => $suggestion->id,
                'dish_name' => $suggestion->dish_name,
                'source_title' => $suggestion->source_title,
                'source_type' => $suggestion->source_type,
                'status' => $suggestion->status,
            ],
        ], 201);
    }
}
