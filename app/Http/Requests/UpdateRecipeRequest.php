<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRecipeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'anime_series_id' => ['sometimes', 'required', 'integer', Rule::exists('anime_series', 'id')],
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'slug' => ['sometimes', 'nullable', 'string', 'max:255', Rule::unique('recipes', 'slug')->ignore($this->route('recipe'))],
            'subtitle' => ['sometimes', 'nullable', 'string', 'max:255'],
            'description' => ['sometimes', 'required', 'string', 'max:3000'],
            'image_path' => ['sometimes', 'required', 'string', 'max:255'],
            'source_label' => ['sometimes', 'nullable', 'string', 'max:255'],
            'ingredients' => ['sometimes', 'required', 'array', 'min:1'],
            'ingredients.*' => ['required', 'string', 'max:255'],
            'steps' => ['sometimes', 'required', 'array', 'min:1'],
            'steps.*' => ['required', 'string', 'max:2000'],
            'servings' => ['sometimes', 'required', 'integer', 'min:1', 'max:30'],
            'prep_minutes' => ['sometimes', 'required', 'integer', 'min:0', 'max:1440'],
            'cook_minutes' => ['sometimes', 'required', 'integer', 'min:0', 'max:1440'],
            'difficulty' => ['sometimes', 'required', Rule::in(['fácil', 'media', 'desafiante'])],
            'is_featured' => ['sometimes', 'boolean'],
            'published_at' => ['sometimes', 'nullable', 'date'],
        ];
    }
}
