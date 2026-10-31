<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAdminRecipeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() === true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'anime_series_id' => ['required', 'integer', 'exists:anime_series,id'],
            'title' => ['required', 'string', 'max:150'],
            'slug' => ['nullable', 'string', 'max:170', 'alpha_dash', 'unique:recipes,slug'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:3000'],
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'source_label' => ['required', 'string', 'max:150'],
            'ingredients' => ['required', 'array', 'min:1'],
            'ingredients.*' => ['required', 'string', 'max:255'],
            'steps' => ['required', 'array', 'min:1'],
            'steps.*' => ['required', 'string', 'max:1500'],
            'servings' => ['required', 'integer', 'min:1', 'max:50'],
            'prep_minutes' => ['required', 'integer', 'min:0', 'max:1440'],
            'cook_minutes' => ['required', 'integer', 'min:0', 'max:1440'],
            'difficulty' => ['required', Rule::in(['fácil', 'media', 'desafiante'])],
            'is_featured' => ['nullable', 'boolean'],
            'publish_now' => ['nullable', 'boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'ingredients' => $this->cleanList($this->input('ingredients', [])),
            'steps' => $this->cleanList($this->input('steps', [])),
        ]);
    }

    /**
     * @param  array<mixed>  $values
     * @return array<int, string>
     */
    private function cleanList(array $values): array
    {
        return array_values(array_filter(array_map(
            fn (mixed $value): string => trim((string) $value),
            $values,
        )));
    }
}
