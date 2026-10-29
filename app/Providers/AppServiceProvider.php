<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void {}

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::preventLazyLoading(! app()->isProduction());

        Passport::enablePasswordGrant();
        Passport::tokensExpireIn(now()->addHour());
        Passport::clientCredentialsTokensExpireIn(now()->addHours(6));
        Passport::refreshTokensExpireIn(now()->addDays(30));
        Passport::personalAccessTokensExpireIn(now()->addMonths(6));
        Passport::tokensCan([
            'integration:connect' => 'Comprobar la conexión entre proyectos.',
            'dish-of-the-day:read' => 'Consultar el platillo del día.',
            'suggestions:create' => 'Enviar propuestas de platillos.',
            'recipes:read' => 'Consultar el catálogo de recetas.',
            'recipes:write' => 'Crear y actualizar recetas.',
            'recipes:delete' => 'Eliminar recetas.',
            'profile:read' => 'Consultar el perfil autenticado.',
            'admin' => 'Acceso administrativo completo.',
        ]);
        Passport::defaultScopes(['recipes:read']);
    }
}
