<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\PartnerConnectionController;
use App\Http\Controllers\Api\V1\RecipeController;
use App\Http\Controllers\Api\V1\RecipeSuggestionController;
use Illuminate\Support\Facades\Route;
use Laravel\Passport\Http\Middleware\CheckToken;
use Laravel\Passport\Http\Middleware\EnsureClientIsResourceOwner;

Route::prefix('v1')->name('api.v1.')->group(function (): void {
    Route::get('/status', fn (): array => [
        'status' => 'ok',
        'service' => config('app.name'),
        'version' => 'v1',
    ])->name('status');

    Route::get('/dish-of-the-day', [RecipeController::class, 'dishOfTheDay'])->name('dish-of-the-day');
    Route::post('/suggestions', [RecipeSuggestionController::class, 'store'])
        ->middleware('throttle:10,1')
        ->name('suggestions.store');

    Route::middleware('auth:api')->prefix('partner')->name('partner.')->group(function (): void {
        Route::get('/connection', PartnerConnectionController::class)
            ->middleware(CheckToken::using('integration:connect'))
            ->name('connection');
        Route::get('/dish-of-the-day', [RecipeController::class, 'dishOfTheDay'])
            ->middleware(CheckToken::using('dish-of-the-day:read'))
            ->name('dish-of-the-day');
        Route::post('/suggestions', [RecipeSuggestionController::class, 'store'])
            ->middleware([CheckToken::using('suggestions:create'), 'throttle:10,1'])
            ->name('suggestions.store');
    });

    Route::middleware(EnsureClientIsResourceOwner::using('recipes:read'))->group(function (): void {
        Route::get('/recipes', [RecipeController::class, 'index'])->name('recipes.index');
        Route::get('/recipes/{recipe}', [RecipeController::class, 'show'])->name('recipes.show');
    });

    Route::post('/recipes', [RecipeController::class, 'store'])
        ->middleware(EnsureClientIsResourceOwner::using('recipes:write'))
        ->name('recipes.store');
    Route::match(['put', 'patch'], '/recipes/{recipe}', [RecipeController::class, 'update'])
        ->middleware(EnsureClientIsResourceOwner::using('recipes:write'))
        ->name('recipes.update');
    Route::delete('/recipes/{recipe}', [RecipeController::class, 'destroy'])
        ->middleware(EnsureClientIsResourceOwner::using('recipes:delete'))
        ->name('recipes.destroy');

    Route::middleware('auth:api')->prefix('account')->name('account.')->group(function (): void {
        Route::get('/me', [AuthController::class, 'me'])
            ->middleware(CheckToken::using('profile:read'))
            ->name('me');
        Route::delete('/logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('/recipes', [RecipeController::class, 'index'])
            ->middleware(CheckToken::using('recipes:read'))
            ->name('recipes');
    });
});
