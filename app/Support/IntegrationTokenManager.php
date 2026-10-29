<?php

namespace App\Support;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;
use Laravel\Passport\ClientRepository;
use Laravel\Passport\PersonalAccessTokenResult;
use Laravel\Passport\Token;
use RuntimeException;

class IntegrationTokenManager
{
    public const array SCOPES = [
        'integration:connect',
        'dish-of-the-day:read',
        'suggestions:create',
    ];

    private const string INTEGRATION_EMAIL = 'integration@meshiverse.local';

    public function __construct(private readonly ClientRepository $clients) {}

    public function issue(string $name): PersonalAccessTokenResult
    {
        $this->ensurePersonalAccessClient();

        return $this->integrationUser()->createToken($name, self::SCOPES);
    }

    /** @return Collection<int, Token> */
    public function activeTokens(): Collection
    {
        $user = User::query()->where('email', self::INTEGRATION_EMAIL)->first();

        if ($user === null) {
            return new Collection;
        }

        return $user->tokens()
            ->where('revoked', false)
            ->where('expires_at', '>', now())
            ->latest()
            ->get();
    }

    public function revoke(string $tokenId): bool
    {
        $user = User::query()->where('email', self::INTEGRATION_EMAIL)->first();

        if ($user === null) {
            return false;
        }

        $token = $user->tokens()->whereKey($tokenId)->first();

        return $token?->revoke() ?? false;
    }

    private function integrationUser(): User
    {
        return User::query()->firstOrCreate(
            ['email' => self::INTEGRATION_EMAIL],
            [
                'name' => 'Conexión MeshiVerse',
                'password' => Str::password(40),
                'email_verified_at' => now(),
            ],
        );
    }

    private function ensurePersonalAccessClient(): void
    {
        try {
            $this->clients->personalAccessClient('users');
        } catch (RuntimeException) {
            $this->clients->createPersonalAccessGrantClient('MeshiVerse Partner Tokens', 'users');
        }
    }
}
