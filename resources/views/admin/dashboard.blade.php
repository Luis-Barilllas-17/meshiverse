@extends('layouts.dashboard')

@section('title', 'Panel administrativo — MeshiVerse')

@section('content')
    <div class="flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="section-kicker">Sala del gremio</p>
            <h1 class="section-title">Panel administrativo</h1>
            <p class="mt-3 text-sm text-cream/50">Controla el recetario, el platillo diario y las conexiones del proyecto.</p>
        </div>
        <a href="{{ route('admin.recipes.create') }}" class="primary-button">＋ Crear nueva receta</a>
    </div>

    <section class="mt-8 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        @foreach ([
            ['label' => 'Recetas', 'value' => $recipeCount, 'detail' => $publishedRecipeCount.' publicadas', 'color' => 'text-gold'],
            ['label' => 'Usuarios', 'value' => $userCount, 'detail' => 'miembros del gremio', 'color' => 'text-mint'],
            ['label' => 'Sugerencias', 'value' => $suggestionCount, 'detail' => 'pendientes de revisar', 'color' => 'text-coral'],
            ['label' => 'Favoritos', 'value' => $favoriteCount, 'detail' => 'estrellas guardadas', 'color' => 'text-lilac'],
        ] as $metric)
            <article class="dashboard-card">
                <p class="text-[10px] font-black uppercase tracking-[.2em] text-cream/35">{{ $metric['label'] }}</p>
                <p class="mt-3 text-4xl font-black {{ $metric['color'] }}">{{ $metric['value'] }}</p>
                <p class="mt-1 text-xs text-cream/40">{{ $metric['detail'] }}</p>
            </article>
        @endforeach
    </section>

    <section class="mt-7 grid gap-7 xl:grid-cols-[1.05fr_.95fr]">
        <article class="dashboard-card overflow-hidden p-0">
            <div class="border-b border-gold/10 px-6 py-5"><p class="section-kicker mb-1">Selección actual</p><h2 class="text-2xl font-black">Platillo del día</h2></div>
            @if ($dishOfTheDay)
                <div class="grid sm:grid-cols-[180px_1fr]">
                    <img src="{{ asset($dishOfTheDay->image_path) }}" alt="{{ $dishOfTheDay->title }}" class="h-52 w-full object-cover sm:h-full">
                    <div class="p-6">
                        <p class="text-xs font-black uppercase tracking-widest text-mint">{{ $dishOfTheDay->animeSeries->name }}</p>
                        <h3 class="mt-2 text-2xl font-black">{{ $dishOfTheDay->title }}</h3>
                        <p class="mt-3 line-clamp-3 text-sm leading-6 text-cream/50">{{ $dishOfTheDay->description }}</p>
                        <a href="{{ route('admin.dish.index') }}" class="secondary-button mt-5">Cambiar selección</a>
                    </div>
                </div>
            @else
                <div class="p-8 text-sm text-cream/50">No hay recetas publicadas para seleccionar.</div>
            @endif
        </article>

        <article class="dashboard-card">
            <div class="flex items-center justify-between"><div><p class="section-kicker mb-1">Actividad</p><h2 class="text-2xl font-black">Recetas recientes</h2></div><a href="{{ route('admin.recipes.create') }}" class="text-sm font-black text-gold">Agregar</a></div>
            <div class="mt-5 divide-y divide-gold/10">
                @foreach ($latestRecipes as $recipe)
                    <div class="flex items-center gap-4 py-3">
                        <img src="{{ asset($recipe->image_path) }}" alt="" class="size-12 rounded-xl object-cover">
                        <div class="min-w-0 flex-1"><p class="truncate text-sm font-black">{{ $recipe->title }}</p><p class="mt-1 text-xs text-cream/35">{{ $recipe->animeSeries->name }}</p></div>
                        <span class="rounded-full px-3 py-1 text-[10px] font-black uppercase {{ $recipe->published_at?->isPast() ? 'bg-mint/10 text-mint' : 'bg-gold/10 text-gold' }}">{{ $recipe->published_at?->isPast() ? 'Publicada' : 'Borrador' }}</span>
                    </div>
                @endforeach
            </div>
        </article>
    </section>
@endsection
