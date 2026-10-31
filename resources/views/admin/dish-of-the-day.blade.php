@extends('layouts.dashboard')

@section('title', 'Receta del día — MeshiVerse')

@section('content')
    <div class="max-w-6xl">
        <p class="section-kicker">Curaduría diaria</p>
        <h1 class="section-title">Receta del día</h1>
        <p class="mt-3 max-w-2xl text-sm leading-6 text-cream/50">Elige una receta manualmente o deja que MeshiVerse cambie la selección automáticamente cada día.</p>

        <div class="mt-8 grid gap-7 xl:grid-cols-[.9fr_1.1fr]">
            <article class="dashboard-card overflow-hidden p-0">
                @if ($dishOfTheDay)
                    <div class="relative h-72"><img src="{{ asset($dishOfTheDay->image_path) }}" alt="{{ $dishOfTheDay->title }}" class="size-full object-cover"><div class="absolute inset-0 bg-gradient-to-t from-ink via-transparent to-transparent"></div><span class="absolute left-5 top-5 rounded-full bg-gold px-4 py-2 text-xs font-black uppercase text-ink">Selección actual</span></div>
                    <div class="p-7"><p class="text-xs font-black uppercase tracking-widest text-mint">{{ $dishOfTheDay->animeSeries->name }}</p><h2 class="mt-2 font-display text-3xl font-black">{{ $dishOfTheDay->title }}</h2><p class="mt-3 line-clamp-3 text-sm leading-6 text-cream/50">{{ $dishOfTheDay->description }}</p><a href="{{ route('recipes.show', $dishOfTheDay) }}" class="secondary-button mt-5">Ver ficha pública</a></div>
                @else
                    <div class="grid min-h-80 place-items-center p-8 text-center text-cream/45">No hay recetas publicadas todavía.</div>
                @endif
            </article>

            <form method="POST" action="{{ route('admin.dish.update') }}" class="dashboard-card">
                @csrf
                @method('PATCH')
                <p class="section-kicker mb-1">Modo de selección</p>
                <h2 class="text-2xl font-black">Configurar portada</h2>
                <div class="mt-6 grid gap-4 sm:grid-cols-2">
                    <label class="cursor-pointer rounded-2xl border border-gold/15 bg-ink/45 p-5 has-[:checked]:border-mint/60 has-[:checked]:bg-mint/10"><input type="radio" name="mode" value="automatic" @checked(old('mode', $setting->mode) === 'automatic') class="sr-only" data-dish-mode><span class="text-2xl">✦</span><strong class="mt-3 block">Automático</strong><span class="mt-2 block text-xs leading-5 text-cream/45">Rotación diaria estable entre todas las recetas publicadas.</span></label>
                    <label class="cursor-pointer rounded-2xl border border-gold/15 bg-ink/45 p-5 has-[:checked]:border-gold/60 has-[:checked]:bg-gold/10"><input type="radio" name="mode" value="manual" @checked(old('mode', $setting->mode) === 'manual') class="sr-only" data-dish-mode><span class="text-2xl">☀</span><strong class="mt-3 block">Manual</strong><span class="mt-2 block text-xs leading-5 text-cream/45">Mantiene la receta elegida hasta que vuelvas a cambiarla.</span></label>
                </div>
                @error('mode')<p class="form-error">{{ $message }}</p>@enderror

                <div class="mt-6" data-manual-recipe>
                    <label for="recipe_id" class="form-label">Receta seleccionada</label>
                    <select id="recipe_id" name="recipe_id" class="form-control">
                        <option value="">Selecciona una receta</option>
                        @foreach ($recipes as $recipe)
                            <option value="{{ $recipe->id }}" @selected(old('recipe_id', $setting->recipe_id) == $recipe->id)>{{ $recipe->title }} — {{ $recipe->animeSeries->name }}{{ $recipe->published_at?->isPast() ? '' : ' (borrador)' }}</option>
                        @endforeach
                    </select>
                    <p class="mt-2 text-xs text-cream/35">Si eliges un borrador, se publicará para que pueda mostrarse como receta del día.</p>
                    @error('recipe_id')<p class="form-error">{{ $message }}</p>@enderror
                </div>
                <button class="primary-button mt-7 w-full">Guardar configuración</button>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    const modeInputs = document.querySelectorAll('[data-dish-mode]');
    const manualRecipe = document.querySelector('[data-manual-recipe]');
    const updateDishMode = () => manualRecipe?.classList.toggle('hidden', document.querySelector('[data-dish-mode]:checked')?.value !== 'manual');
    modeInputs.forEach((input) => input.addEventListener('change', updateDishMode));
    updateDishMode();
</script>
@endpush
