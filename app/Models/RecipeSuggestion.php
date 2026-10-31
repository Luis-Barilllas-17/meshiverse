<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['user_id', 'dish_name', 'source_title', 'source_type', 'reason', 'email', 'status'])]
class RecipeSuggestion extends Model
{
    use HasFactory;

    /** @var array<string, mixed> */
    protected $attributes = [
        'status' => 'pending',
    ];

    /** @return BelongsTo<User, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
