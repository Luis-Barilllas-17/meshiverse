<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminRecipeController;
use App\Http\Controllers\Admin\DishOfTheDayController;
use App\Http\Controllers\Admin\HamaDataController;
use App\Http\Controllers\AuthenticatedSessionController;
use App\Http\Controllers\FavoriteRecipeController;
use App\Http\Controllers\IntegrationDashboardController;
use App\Http\Controllers\IntegrationProbeController;
use App\Http\Controllers\IntegrationTokenController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RecipePageController;
use App\Http\Controllers\RecipeSuggestionController;
use App\Http\Controllers\UserDashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', [RecipePageController::class, 'index'])->name('home');
Route::get('/recetas/{recipe}', [RecipePageController::class, 'show'])->name('recipes.show');

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.store');
});

Route::middleware('auth')->group(function (): void {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    Route::get('/panel', UserDashboardController::class)->name('user.dashboard');
    Route::get('/favoritos', [FavoriteRecipeController::class, 'index'])->name('favorites.index');
    Route::post('/favoritos/{recipe}', [FavoriteRecipeController::class, 'store'])->name('favorites.store');
    Route::delete('/favoritos/{recipe}', [FavoriteRecipeController::class, 'destroy'])->name('favorites.destroy');
    Route::get('/sugerencias', [RecipeSuggestionController::class, 'create'])->name('suggestions.create');
    Route::post('/sugerencias', [RecipeSuggestionController::class, 'store'])
        ->middleware('throttle:10,1')
        ->name('suggestions.store');
    Route::get('/perfil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/perfil', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/perfil/contrasena', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function (): void {
    Route::get('/', AdminDashboardController::class)->name('dashboard');
    Route::get('/recetas/crear', [AdminRecipeController::class, 'create'])->name('recipes.create');
    Route::post('/recetas', [AdminRecipeController::class, 'store'])->name('recipes.store');
    Route::get('/receta-del-dia', [DishOfTheDayController::class, 'index'])->name('dish.index');
    Route::patch('/receta-del-dia', [DishOfTheDayController::class, 'update'])->name('dish.update');

    Route::middleware('local.only')->prefix('configuracion')->group(function (): void {
        Route::get('/api', IntegrationDashboardController::class)->name('api.docs');
        Route::post('/api/tokens', [IntegrationTokenController::class, 'store'])
            ->middleware('throttle:5,1')
            ->name('api.tokens.store');
        Route::delete('/api/tokens/{token}', [IntegrationTokenController::class, 'destroy'])
            ->name('api.tokens.destroy');
        Route::post('/api/probar', IntegrationProbeController::class)
            ->middleware('throttle:10,1')
            ->name('api.connections.test');
        Route::get('/api/hamas', [HamaDataController::class, 'index'])
            ->middleware('throttle:30,1')
            ->name('api.hamas.index');
        Route::post('/api/hamas', [HamaDataController::class, 'store'])
            ->middleware('throttle:10,1')
            ->name('api.hamas.store');
        Route::delete('/api/hamas/conexion', [HamaDataController::class, 'destroy'])
            ->name('api.hamas.connection.destroy');
    });
});

Route::get('/api', fn () => to_route('admin.api.docs'))
    ->middleware(['auth', 'admin'])
    ->name('api.docs');
