<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function me(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();
        $tokenAttributes = $user->token()?->toArray() ?? [];

        return response()->json([
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'scopes' => $tokenAttributes['oauth_scopes'] ?? $tokenAttributes['scopes'] ?? [],
            ],
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();
        $token = $user->token();

        $token?->refreshToken?->revoke();
        $token?->revoke();

        return response()->json(['message' => 'Sesión cerrada correctamente.']);
    }
}
