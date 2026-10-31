<?php

namespace App\Http\Controllers;

use App\Models\PartnerConnection;
use App\Support\DishOfTheDay;
use App\Support\IntegrationTokenManager;
use Illuminate\View\View;

class IntegrationDashboardController extends Controller
{
    public function __invoke(IntegrationTokenManager $tokens, DishOfTheDay $dishOfTheDay): View
    {
        return view('api-docs', [
            'activeTokens' => $tokens->activeTokens(),
            'dishOfTheDay' => $dishOfTheDay->get(),
            'baseUrl' => rtrim(config('app.url'), '/'),
            'hamaConnection' => PartnerConnection::query()
                ->where('provider', PartnerConnection::PROVIDER_HAMA)
                ->first(),
        ]);
    }
}
