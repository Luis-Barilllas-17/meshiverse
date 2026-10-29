@props(['ingredient'])

@php
    $normalizedIngredient = \Illuminate\Support\Str::lower(\Illuminate\Support\Str::ascii($ingredient));
    $icon = match (true) {
        \Illuminate\Support\Str::contains($normalizedIngredient, ['pollo', 'ave']) => '🍗',
        \Illuminate\Support\Str::contains($normalizedIngredient, ['carne', 'res', 'cerdo', 'tocino', 'costilla', 'salchicha']) => '🥩',
        \Illuminate\Support\Str::contains($normalizedIngredient, ['pescado', 'salmon', 'atun', 'caballa', 'marisco', 'camaron', 'cangrejo']) => '🐟',
        \Illuminate\Support\Str::contains($normalizedIngredient, ['arroz', 'onigiri']) => '🍚',
        \Illuminate\Support\Str::contains($normalizedIngredient, ['fideo', 'soba', 'pasta', 'ramen']) => '🍜',
        \Illuminate\Support\Str::contains($normalizedIngredient, ['huevo']) => '🥚',
        \Illuminate\Support\Str::contains($normalizedIngredient, ['leche', 'crema', 'mantequilla', 'queso']) => '🧀',
        \Illuminate\Support\Str::contains($normalizedIngredient, ['harina', 'pan', 'tortilla']) => '🌾',
        \Illuminate\Support\Str::contains($normalizedIngredient, ['hongo', 'champi']) => '🍄',
        \Illuminate\Support\Str::contains($normalizedIngredient, ['manzana', 'fresa', 'lima', 'limon', 'fruta']) => '🍎',
        \Illuminate\Support\Str::contains($normalizedIngredient, ['azucar', 'miel', 'chocolate', 'vainilla']) => '🍯',
        \Illuminate\Support\Str::contains($normalizedIngredient, ['sal', 'pimienta', 'soya', 'salsa', 'especia', 'canela']) => '🧂',
        \Illuminate\Support\Str::contains($normalizedIngredient, ['cebolla', 'zanahoria', 'papa', 'repollo', 'lechuga', 'vegetal', 'pepino', 'pimiento', 'esparrago']) => '🥕',
        \Illuminate\Support\Str::contains($normalizedIngredient, ['agua', 'caldo', 'te', 'vino', 'sake']) => '🥣',
        default => '✦',
    };
@endphp

<span {{ $attributes->class('grid size-8 shrink-0 place-items-center rounded-xl border border-white/10 bg-white/[0.06] text-base shadow-sm') }} aria-hidden="true">{{ $icon }}</span>
