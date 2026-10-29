<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRecipeSuggestionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, ValidationRule|array<mixed>|string> */
    public function rules(): array
    {
        return [
            'dish_name' => ['required', 'string', 'max:150'],
            'source_title' => ['required', 'string', 'max:150'],
            'source_type' => ['required', Rule::in(['anime', 'videojuego', 'otro'])],
            'reason' => ['nullable', 'string', 'max:1000'],
            'email' => ['nullable', 'email', 'max:255'],
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'dish_name.required' => 'Escribe el nombre del platillo que quieres sugerir.',
            'source_title.required' => 'Indica de qué anime o videojuego proviene.',
            'source_type.in' => 'Selecciona anime, videojuego u otro origen.',
        ];
    }
}
