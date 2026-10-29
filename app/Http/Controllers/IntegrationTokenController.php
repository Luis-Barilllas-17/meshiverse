<?php

namespace App\Http\Controllers;

use App\Http\Requests\IssueIntegrationTokenRequest;
use App\Support\IntegrationTokenManager;
use Illuminate\Http\RedirectResponse;

class IntegrationTokenController extends Controller
{
    public function store(IssueIntegrationTokenRequest $request, IntegrationTokenManager $tokens): RedirectResponse
    {
        $result = $tokens->issue($request->string('token_name')->toString());

        return to_route('api.docs')->with([
            'success' => 'Token de conexión generado. Cópialo ahora: por seguridad solo se muestra una vez.',
            'generated_token' => $result->accessToken,
            'generated_token_expires_at' => $result->token?->expires_at?->toIso8601String(),
        ]);
    }

    public function destroy(string $token, IntegrationTokenManager $tokens): RedirectResponse
    {
        abort_unless($tokens->revoke($token), 404);

        return to_route('api.docs')->with('success', 'El token fue revocado y ya no puede conectarse.');
    }
}
