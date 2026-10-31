<?php

namespace App\Models;

use Database\Factories\RecipeFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[Fillable([
    'anime_series_id', 'title', 'slug', 'subtitle', 'description', 'image_path',
    'source_label', 'ingredients', 'steps', 'servings', 'prep_minutes',
    'cook_minutes', 'difficulty', 'is_featured', 'published_at',
])]
class Recipe extends Model
{
    /** @use HasFactory<RecipeFactory> */
    use HasFactory;

    /** @return BelongsTo<AnimeSeries, $this> */
    public function animeSeries(): BelongsTo
    {
        return $this->belongsTo(AnimeSeries::class);
    }

    /** @return BelongsToMany<User, $this> */
    public function favoritedBy(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'favorite_recipes')->withTimestamps();
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'ingredients' => 'array',
            'steps' => 'array',
            'is_featured' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    #[Scope]
    protected function published(Builder $query): void
    {
        $query->whereNotNull('published_at')->where('published_at', '<=', now());
    }

    #[Scope]
    protected function filtered(Builder $query, ?string $search, ?string $anime, ?string $difficulty): void
    {
        $query
            ->when($search, fn (Builder $query, string $search): Builder => $query->where(function (Builder $query) use ($search): void {
                $query->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            }))
            ->when($anime, fn (Builder $query, string $anime): Builder => $query->whereHas(
                'animeSeries',
                fn (Builder $query): Builder => $query->where('slug', $anime),
            ))
            ->when($difficulty, fn (Builder $query, string $difficulty): Builder => $query->where('difficulty', $difficulty));
    }
}
