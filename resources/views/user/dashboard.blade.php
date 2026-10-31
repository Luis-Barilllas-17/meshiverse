@extends('layouts.dashboard')

@section('title', 'Mi panel — MeshiVerse')

@section('content')
    <div class="flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">
        <div><p class="section-kicker">Diario del aventurero</p><h1 class="section-title">Hola, {{ auth()->user()->name }}</h1><p class="mt-3 text-sm text-cream/50">Aquí están tus recetas guardadas y propuestas para el próximo menú.</p></div>
        <a href="{{ route('home') }}#recetas" class="primary-button">Explorar recetas</a>
    </div>

    <section class="mt-8 grid gap-4 sm:grid-cols-2">
        <article class="dashboard-card bg-gradient-to-br from-gold/15 to-night"><p class="text-[10px] font-black uppercase tracking-[.2em] text-gold">Favoritos</p><p class="mt-3 text-4xl font-black">{{ $favoriteCount }}</p><p class="mt-1 text-xs text-cream/40">recetas marcadas con estrella</p></article>
        <article class="dashboard-card bg-gradient-to-br from-mint/15 to-night"><p class="text-[10px] font-black uppercase tracking-[.2em] text-mint">Sugerencias</p><p class="mt-3 text-4xl font-black">{{ $suggestionCount }}</p><p class="mt-1 text-xs text-cream/40">propuestas enviadas</p></article>
    </section>

    <section class="mt-8">
        <div class="mb-5 flex items-end justify-between"><div><p class="section-kicker mb-1">Tu colección</p><h2 class="font-display text-3xl font-black">Favoritos recientes</h2></div><a href="{{ route('favorites.index') }}" class="text-sm font-black text-gold">Ver todos →</a></div>
        @if ($favoriteRecipes->isNotEmpty())
            <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-4">
                @foreach ($favoriteRecipes as $recipe)
                    <x-recipe-card :recipe="$recipe" :is-favorite="true" />
                @endforeach
            </div>
        @else
            <div class="dashboard-card border-dashed py-12 text-center"><p class="text-4xl text-gold">☆</p><h3 class="mt-3 text-xl font-black">Tu colección está vacía</h3><p class="mt-2 text-sm text-cream/45">Usa la estrella de cualquier receta para guardarla aquí.</p><a href="{{ route('home') }}#recetas" class="secondary-button mt-5">Buscar recetas</a></div>
        @endif
    </section>

    <section class="mt-8 dashboard-card">
        <div class="flex flex-wrap items-center justify-between gap-4"><div><p class="section-kicker mb-1">Voz de la comunidad</p><h2 class="text-2xl font-black">Mis sugerencias</h2></div><a href="{{ route('suggestions.create') }}" class="secondary-button">＋ Nueva sugerencia</a></div>
        <div class="mt-5 divide-y divide-gold/10">
            @forelse ($suggestions as $suggestion)
                <div class="flex flex-col gap-2 py-4 sm:flex-row sm:items-center sm:justify-between"><div><p class="font-black">{{ $suggestion->dish_name }}</p><p class="mt-1 text-xs text-cream/40">{{ $suggestion->source_title }} · {{ ucfirst($suggestion->source_type) }}</p></div><span class="w-fit rounded-full bg-gold/10 px-3 py-1 text-[10px] font-black uppercase text-gold">{{ $suggestion->status }}</span></div>
            @empty
                <p class="py-7 text-sm text-cream/40">Todavía no has propuesto ningún platillo.</p>
            @endforelse
        </div>
    </section>
@endsection
