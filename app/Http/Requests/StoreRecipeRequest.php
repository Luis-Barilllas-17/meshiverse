<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRecipeRequest extends FormRequest
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
            'anime_series_id' => ['required', 'integer', Rule::exists('anime_series', 'id')],
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('recipes', 'slug')],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:3000'],
            'image_path' => ['required', 'string', 'max:255'],
            'source_label' => ['nullable', 'string', 'max:255'],
            'ingredients' => ['required', 'array', 'min:1'],
            'ingredients.*' => ['required', 'string', 'max:255'],
            'steps' => ['required', 'array', 'min:1'],
            'steps.*' => ['required', 'string', 'max:2000'],
            'servings' => ['required', 'integer', 'min:1', 'max:30'],
            'prep_minutes' => ['required', 'integer', 'min:0', 'max:1440'],
            'cook_minutes' => ['required', 'integer', 'min:0', 'max:1440'],
            'difficulty' => ['required', Rule::in(['fácil', 'media', 'desafiante'])],
            'is_featured' => ['sometimes', 'boolean'],
            'published_at' => ['nullable', 'date'],
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'anime_series_id.required' => 'Selecciona el anime al que pertenece la receta.',
            'ingredients.min' => 'Agrega por lo menos un ingrediente.',
            'steps.min' => 'Agrega por lo menos un paso de preparación.',
            'difficulty.in' => 'La dificultad debe ser fácil, media o desafiante.',
        ];
    }
}
