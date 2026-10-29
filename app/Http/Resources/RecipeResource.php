<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RecipeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'subtitle' => $this->subtitle,
            'description' => $this->description,
            'image_url' => asset($this->image_path),
            'source_label' => $this->source_label,
            'anime' => new AnimeSeriesResource($this->whenLoaded('animeSeries')),
            'ingredients' => $this->ingredients,
            'steps' => $this->steps,
            'servings' => $this->servings,
            'time' => [
                'prep_minutes' => $this->prep_minutes,
                'cook_minutes' => $this->cook_minutes,
                'total_minutes' => $this->prep_minutes + $this->cook_minutes,
            ],
            'difficulty' => $this->difficulty,
            'is_featured' => $this->is_featured,
            'published_at' => $this->published_at?->toIso8601String(),
            'links' => [
                'web' => route('recipes.show', $this->resource),
                'api' => route('api.v1.recipes.show', $this->resource),
            ],
        ];
    }
}
