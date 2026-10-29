<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\RecipeResource;
use App\Models\User;
use App\Support\DishOfTheDay;
use App\Support\IntegrationTokenManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PartnerConnectionController extends Controller
{
    public function __invoke(Request $request, DishOfTheDay $dishOfTheDay): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();
        $dish = $dishOfTheDay->get();
        $tokenAttributes = $user->token()?->toArray() ?? [];

        return response()->json([
            'status' => 'connected',
            'service' => config('app.name'),
            'message' => 'Conexión autenticada correctamente. Ambos proyectos pueden intercambiar datos.',
            'authenticated_as' => [
                'name' => $user->name,
                'scopes' => $tokenAttributes['oauth_scopes'] ?? $tokenAttributes['scopes'] ?? IntegrationTokenManager::SCOPES,
            ],
            'sharing' => [
                'dish_of_the_day' => [
                    'label' => 'Platillo del día',
                    'direction' => 'MeshiVerse → proyecto conectado',
                    'method' => 'GET',
                    'endpoint' => route('api.v1.partner.dish-of-the-day'),
                    'scope' => 'dish-of-the-day:read',
                ],
                'recipe_suggestions' => [
                    'label' => 'Encuesta de propuestas de comida',
                    'direction' => 'Proyecto conectado → MeshiVerse',
                    'method' => 'POST',
                    'endpoint' => route('api.v1.partner.suggestions.store'),
                    'scope' => 'suggestions:create',
                    'fields' => ['dish_name', 'source_title', 'source_type', 'reason', 'email'],
                ],
            ],
            'preview' => [
                'dish_of_the_day' => $dish === null ? null : (new RecipeResource($dish))->resolve($request),
            ],
            'checked_at' => now()->toIso8601String(),
        ]);
    }
}
