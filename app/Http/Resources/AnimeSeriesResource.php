<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AnimeSeriesResource extends JsonResource
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
            'name' => $this->name,
            'slug' => $this->slug,
            'tagline' => $this->tagline,
            'description' => $this->description,
            'icon' => $this->icon,
            'image_path' => $this->image_path,
            'image_url' => $this->image_path ? asset($this->image_path) : null,
            'colors' => [
                'from' => $this->color_from,
                'to' => $this->color_to,
            ],
        ];
    }
}
