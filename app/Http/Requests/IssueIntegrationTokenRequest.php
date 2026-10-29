<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class IssueIntegrationTokenRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, ValidationRule|array<mixed>|string> */
    public function rules(): array
    {
        return [
            'token_name' => ['required', 'string', 'min:3', 'max:80'],
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'token_name.required' => 'Escribe un nombre para identificar qué proyecto usará el token.',
            'token_name.min' => 'El nombre del token debe tener al menos 3 caracteres.',
        ];
    }
}
