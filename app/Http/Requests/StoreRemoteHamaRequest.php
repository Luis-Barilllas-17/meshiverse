<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreRemoteHamaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, ValidationRule|array<mixed>|string> */
    public function rules(): array
    {
        return [
            'nombre' => ['required', 'string', 'max:255'],
            'descripcion' => ['nullable', 'string', 'max:5000'],
            'ancho' => ['required', 'integer', 'min:1', 'max:50'],
            'alto' => ['required', 'integer', 'min:1', 'max:50'],
            'color_principal' => ['required', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'patron_json' => ['required', 'string', 'json', 'max:200000'],
        ];
    }

    /** @return array<int, callable(Validator): void> */
    public function after(): array
    {
        return [
            function (Validator $validator): void {
                if ($validator->errors()->hasAny(['ancho', 'alto', 'patron_json'])) {
                    return;
                }

                $pattern = $this->pattern();

                if ($pattern === null || $pattern === []) {
                    $validator->errors()->add('patron_json', 'Colorea al menos una cuenta en el tablero.');

                    return;
                }

                if (count($pattern) > 2500) {
                    $validator->errors()->add('patron_json', 'El patrón no puede contener más de 2500 cuentas.');

                    return;
                }

                $maximumPosition = ($this->integer('ancho') * $this->integer('alto')) - 1;
                $usedPositions = [];

                foreach ($pattern as $point) {
                    if (! is_array($point)
                        || ! isset($point['posicion'], $point['color'])
                        || ! is_int($point['posicion'])
                        || $point['posicion'] < 0
                        || $point['posicion'] > $maximumPosition
                        || ! is_string($point['color'])
                        || preg_match('/^#[0-9A-Fa-f]{6}$/', $point['color']) !== 1
                    ) {
                        $validator->errors()->add('patron_json', 'El patrón contiene una posición o un color inválido.');

                        return;
                    }

                    if (isset($usedPositions[$point['posicion']])) {
                        $validator->errors()->add('patron_json', 'Cada posición del patrón debe ser única.');

                        return;
                    }

                    $usedPositions[$point['posicion']] = true;
                }
            },
        ];
    }

    /** @return array<string, mixed> */
    public function hamaPayload(): array
    {
        $validated = $this->validated();
        $pattern = $this->pattern() ?? [];

        return [
            'nombre' => $validated['nombre'],
            'descripcion' => $validated['descripcion'] ?? null,
            'ancho' => (int) $validated['ancho'],
            'alto' => (int) $validated['alto'],
            'cantidad_cuentas' => count($pattern),
            'color_principal' => strtoupper($validated['color_principal']),
            'patron' => $pattern,
        ];
    }

    /** @return array<int, array{posicion: int, color: string}>|null */
    private function pattern(): ?array
    {
        $pattern = json_decode($this->string('patron_json')->toString(), true);

        return is_array($pattern) ? $pattern : null;
    }
}
