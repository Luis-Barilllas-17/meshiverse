<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class ProbeIntegrationConnectionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, ValidationRule|array<mixed>|string> */
    public function rules(): array
    {
        return [
            'partner_url' => ['required', 'url:http,https', 'max:255'],
            'partner_token' => ['required', 'string', 'min:20', 'max:4096'],
            'save_connection' => ['sometimes', 'boolean'],
        ];
    }

    /** @return array<int, callable(Validator): void> */
    public function after(): array
    {
        return [
            function (Validator $validator): void {
                if ($validator->errors()->has('partner_url')) {
                    return;
                }

                $parts = parse_url($this->string('partner_url')->toString());

                if ($parts === false || isset($parts['user'], $parts['pass']) || isset($parts['query']) || isset($parts['fragment'])) {
                    $validator->errors()->add('partner_url', 'Usa solo la URL base, sin credenciales, parámetros ni fragmentos.');
                }
            },
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'partner_url.required' => 'Indica la URL del otro proyecto.',
            'partner_url.url' => 'La URL debe comenzar con http:// o https://.',
            'partner_token.required' => 'Pega el token generado por el otro proyecto.',
            'partner_token.min' => 'El token recibido no parece válido.',
        ];
    }
}
