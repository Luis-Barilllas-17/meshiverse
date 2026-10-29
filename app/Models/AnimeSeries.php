<?php

namespace App\Models;

use Database\Factories\AnimeSeriesFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'slug', 'tagline', 'description', 'color_from', 'color_to', 'icon', 'image_path', 'sort_order'])]
class AnimeSeries extends Model
{
    /** @use HasFactory<AnimeSeriesFactory> */
    use HasFactory;

    /** @return HasMany<Recipe, $this> */
    public function recipes(): HasMany
    {
        return $this->hasMany(Recipe::class);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
