<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\Contracts\OAuthenticatable as OAuthenticatableContract;
use Laravel\Passport\HasApiTokens;

#[Fillable(['name', 'email', 'role', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements OAuthenticatableContract
{
    public const ROLE_ADMIN = 'admin';

    public const ROLE_USER = 'user';

    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /** @return BelongsToMany<Recipe, $this> */
    public function favoriteRecipes(): BelongsToMany
    {
        return $this->belongsToMany(Recipe::class, 'favorite_recipes')->withTimestamps();
    }

    /** @return HasMany<RecipeSuggestion, $this> */
    public function recipeSuggestions(): HasMany
    {
        return $this->hasMany(RecipeSuggestion::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
