<?php

use App\Http\Controllers\IntegrationDashboardController;
use App\Http\Controllers\IntegrationProbeController;
use App\Http\Controllers\IntegrationTokenController;
use App\Http\Controllers\RecipePageController;
use App\Http\Controllers\RecipeSuggestionController;
use Illuminate\Support\Facades\Route;

Route::get('/', [RecipePageController::class, 'index'])->name('home');
Route::get('/recetas/{recipe}', [RecipePageController::class, 'show'])->name('recipes.show');
Route::middleware('local.only')->group(function (): void {
    Route::get('/api', IntegrationDashboardController::class)->name('api.docs');
    Route::post('/connection-center/tokens', [IntegrationTokenController::class, 'store'])
        ->middleware('throttle:5,1')
        ->name('api.tokens.store');
    Route::delete('/connection-center/tokens/{token}', [IntegrationTokenController::class, 'destroy'])
        ->name('api.tokens.destroy');
    Route::post('/connection-center/test', IntegrationProbeController::class)
        ->middleware('throttle:10,1')
        ->name('api.connections.test');
});
Route::get('/sugerencias', [RecipeSuggestionController::class, 'create'])->name('suggestions.create');
Route::post('/sugerencias', [RecipeSuggestionController::class, 'store'])
    ->middleware('throttle:10,1')
    ->name('suggestions.store');
