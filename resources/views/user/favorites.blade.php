@extends('layouts.dashboard')

@section('title', 'Mis favoritos — MeshiVerse')

@section('content')
    <div class="flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between"><div><p class="section-kicker">Colección personal</p><h1 class="section-title">Mis favoritos</h1><p class="mt-3 text-sm text-cream/50">Las recetas que marcaste con estrella para encontrarlas rápidamente.</p></div><a href="{{ route('home') }}#recetas" class="secondary-button">Explorar más</a></div>

    <div class="mt-8 grid gap-6 md:grid-cols-2 xl:grid-cols-3">
        @forelse ($recipes as $recipe)
            <x-recipe-card :recipe="$recipe" :is-favorite="true" />
        @empty
            <div class="dashboard-card border-dashed py-16 text-center md:col-span-2 xl:col-span-3"><p class="text-5xl text-gold">☆</p><h2 class="mt-4 text-2xl font-black">Aún no guardaste recetas</h2><p class="mt-2 text-sm text-cream/45">Busca la estrella dorada en cada tarjeta del recetario.</p><a href="{{ route('home') }}#recetas" class="primary-button mt-6">Ir al recetario</a></div>
        @endforelse
    </div>
    @if ($recipes->hasPages())<div class="mt-10">{{ $recipes->links() }}</div>@endif
@endsection
