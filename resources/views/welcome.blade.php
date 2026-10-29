@extends('layouts.app')

@section('title', 'MeshiVerse — Recetas de anime que sí puedes cocinar')

@section('content')
    <section class="relative overflow-hidden px-5 pb-20 pt-14 lg:px-8 lg:pb-28 lg:pt-20">
        <div class="speed-lines pointer-events-none absolute -right-40 top-0 size-[42rem] opacity-30" aria-hidden="true"></div>
        <div class="mx-auto grid max-w-7xl items-center gap-14 lg:grid-cols-[1.05fr_.95fr]">
            <div class="relative z-10" data-reveal>
                <div class="mb-7 inline-flex items-center gap-2 rounded-full border border-gold/30 bg-gold/10 px-4 py-2 text-xs font-extrabold uppercase tracking-[0.18em] text-gold">
                    <span class="size-2 animate-pulse rounded-full bg-gold"></span>
                    Recetas desbloqueadas del anime
                </div>
                <h1 class="text-balance text-5xl font-black leading-[0.92] tracking-[-0.055em] sm:text-6xl lg:text-[5.4rem]">
                    Donde el anime<br>
                    <span class="bg-gradient-to-r from-coral via-gold to-mint bg-clip-text text-transparent">se sirve en la mesa.</span>
                </h1>
                <p class="mt-7 max-w-xl text-base leading-7 text-cream/60 sm:text-lg">Descubre platos inolvidables de <strong class="text-cream">One Piece</strong>, <strong class="text-cream">Dr. Stone</strong>, <strong class="text-cream">Food Wars</strong>, <strong class="text-cream">Fate</strong> y <strong class="text-cream">Monster Hunter</strong>, adaptados para cocinar en el mundo real.</p>
                <div class="mt-9 flex flex-wrap gap-4">
                    <a href="#recetas" class="rounded-full bg-coral px-6 py-3.5 text-sm font-black text-white shadow-xl shadow-coral/20 transition hover:-translate-y-1 hover:bg-[#ff7b5f]">Explorar recetas</a>
                    <a href="{{ route('api.docs') }}" class="rounded-full border border-white/15 bg-white/5 px-6 py-3.5 text-sm font-black text-cream transition hover:-translate-y-1 hover:border-mint/50 hover:bg-mint/10">Conectar la API <span class="text-mint">↗</span></a>
                </div>
                <div class="mt-12 grid max-w-lg grid-cols-3 divide-x divide-white/10 border-y border-white/10 py-5">
                    <div><strong class="block text-2xl font-black text-gold">{{ $recipeCount }}</strong><span class="text-xs uppercase tracking-wider text-cream/40">Recetas</span></div>
                    <div class="pl-5"><strong class="block text-2xl font-black text-mint">{{ $series->count() }}</strong><span class="text-xs uppercase tracking-wider text-cream/40">Universos</span></div>
                    <div class="pl-5"><strong class="block text-2xl font-black text-lilac">{{ $ingredientCount }}+</strong><span class="text-xs uppercase tracking-wider text-cream/40">Ingredientes</span></div>
                </div>
            </div>

            <div class="relative mx-auto w-full max-w-xl lg:mr-0" data-reveal>
                <div class="absolute -left-8 top-16 z-20 rotate-[-7deg] rounded-2xl border-4 border-ink bg-gold px-5 py-3 text-sm font-black uppercase text-ink shadow-xl">¡Itadakimasu!</div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="mt-12 overflow-hidden rounded-[2.5rem] border border-white/15 bg-night p-2 shadow-2xl shadow-black/40">
                        <img src="{{ asset('images/recipes/6e7a6bed-08f5-4750-b3d2-2325dd47a17d.jpg') }}" alt="Carne en hueso inspirada en One Piece" class="aspect-[3/4] size-full rounded-[2rem] object-cover">
                    </div>
                    <div class="grid gap-4">
                        <div class="overflow-hidden rounded-[2.5rem] border border-white/15 bg-night p-2 shadow-2xl shadow-black/40">
                            <img src="{{ asset('images/recipes/4af33ee7-1013-4234-b3a6-d28a984936a9.jpg') }}" alt="Curry inspirado en Delicious in Dungeon" class="aspect-square size-full rounded-[2rem] object-cover">
                        </div>
                        <div class="rounded-[2rem] border border-mint/25 bg-mint/10 p-5 backdrop-blur">
                            <p class="text-xs font-black uppercase tracking-[0.2em] text-mint">Edición actual</p>
                            <p class="mt-2 text-xl font-black leading-tight">Banquetes piratas & cocina del calabozo</p>
                        </div>
                    </div>
                </div>
                <div class="absolute -bottom-8 -right-3 z-20 max-w-[13rem] rotate-3 rounded-2xl border-4 border-ink bg-cream p-4 text-ink shadow-xl">
                    <p class="text-xs font-black uppercase tracking-widest text-coral">Dato del chef</p>
                    <p class="mt-1 text-sm font-bold leading-5">Cada receta incluye una adaptación posible fuera del anime.</p>
                </div>
            </div>
        </div>
    </section>

    @if ($dishOfTheDay && ! array_filter($filters))
        <section class="px-5 pb-10 lg:px-8">
            <div class="mx-auto grid max-w-7xl overflow-hidden rounded-[2.75rem] border border-gold/25 bg-gradient-to-br from-gold/15 via-night to-coral/10 shadow-2xl lg:grid-cols-[.8fr_1.2fr]" data-reveal>
                <div class="relative min-h-72 overflow-hidden lg:min-h-[25rem]">
                    <img src="{{ asset($dishOfTheDay->image_path) }}" alt="{{ $dishOfTheDay->title }}" class="absolute inset-0 size-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-night via-transparent to-transparent lg:bg-gradient-to-r lg:from-transparent lg:to-night"></div>
                    <span class="absolute left-5 top-5 rounded-full bg-gold px-4 py-2 text-xs font-black uppercase tracking-[0.18em] text-ink">Selección de hoy</span>
                </div>
                <div class="flex flex-col justify-center p-8 sm:p-12">
                    <p class="section-kicker">Platillo del día · {{ now()->translatedFormat('j \d\e F') }}</p>
                    <h2 class="text-balance text-3xl font-black tracking-[-0.04em] sm:text-5xl">{{ $dishOfTheDay->title }}</h2>
                    <p class="mt-4 max-w-2xl text-base leading-7 text-cream/60">{{ $dishOfTheDay->description }}</p>
                    <div class="mt-6 flex flex-wrap items-center gap-3">
                        <span class="metadata-pill gap-2 py-1.5 pl-1.5 pr-3">
                            <img src="{{ asset($dishOfTheDay->animeSeries->image_path) }}" alt="" class="size-7 rounded-full object-cover" aria-hidden="true">
                            <strong>{{ $dishOfTheDay->animeSeries->name }}</strong>
                        </span>
                        <span class="metadata-pill"><strong>{{ $dishOfTheDay->prep_minutes + $dishOfTheDay->cook_minutes }}</strong> min</span>
                    </div>
                    <a href="{{ route('recipes.show', $dishOfTheDay) }}" class="mt-8 w-fit rounded-full bg-cream px-6 py-3 text-sm font-black text-ink transition hover:-translate-y-1 hover:bg-gold">Cocinar esta receta →</a>
                </div>
            </div>
        </section>
    @endif

    <section id="universos" class="scroll-mt-28 px-5 py-20 lg:px-8">
        <div class="mx-auto max-w-7xl">
            <div class="mb-10 flex flex-col justify-between gap-4 md:flex-row md:items-end" data-reveal>
                <div>
                    <p class="section-kicker">Elige tu aventura</p>
                    <h2 class="section-title">Seis universos. Un hambre legendaria.</h2>
                </div>
                <p class="max-w-md text-sm leading-6 text-cream/50">Navega por la cocina de alta mar o desciende al calabozo para encontrar el próximo plato de tu mesa.</p>
            </div>
            <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
                @foreach ($series as $anime)
                    <a href="{{ route('home', ['anime' => $anime->slug]) }}#recetas" class="group relative min-h-96 overflow-hidden rounded-[2.5rem] border border-white/10 p-8 shadow-2xl transition hover:-translate-y-2 hover:border-white/25 sm:p-10" style="background-color: #12182b" data-reveal>
                        <img src="{{ asset($anime->image_path) }}" alt="Portada de {{ $anime->name }}" class="absolute inset-0 size-full object-cover opacity-60 transition duration-700 group-hover:scale-110 group-hover:opacity-75">
                        <div class="absolute inset-0 bg-gradient-to-t from-ink via-ink/75 to-ink/10"></div>
                        <div class="absolute inset-0 opacity-40" style="background: linear-gradient(135deg, {{ $anime->color_from }}44, transparent 50%, {{ $anime->color_to }}33)"></div>
                        <div class="relative flex h-full flex-col justify-between">
                            <div class="flex items-start justify-between">
                                <span class="overflow-hidden rounded-2xl border border-white/25 bg-ink/50 p-1 shadow-xl backdrop-blur">
                                    <img src="{{ asset($anime->image_path) }}" alt="" class="size-14 rounded-xl object-cover" aria-hidden="true">
                                </span>
                                <span class="rounded-full border border-white/20 bg-ink/65 px-4 py-2 text-xs font-bold text-cream backdrop-blur">{{ $anime->recipes_count }} recetas</span>
                            </div>
                            <div class="mt-20">
                                <p class="mb-2 text-xs font-black uppercase tracking-[0.2em]" style="color: {{ $anime->color_from }}">{{ $anime->tagline }}</p>
                                <h3 class="text-3xl font-black tracking-tight sm:text-4xl">{{ $anime->name }}</h3>
                                <p class="mt-3 max-w-lg text-sm leading-6 text-cream/55">{{ $anime->description }}</p>
                                <span class="mt-6 inline-flex items-center gap-2 text-sm font-black">Entrar al universo <span class="transition group-hover:translate-x-2">→</span></span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    @if ($featuredRecipes->isNotEmpty())
        <section class="border-y border-white/10 bg-white/[0.025] px-5 py-20 lg:px-8">
            <div class="mx-auto max-w-7xl">
                <div class="mb-10" data-reveal>
                    <p class="section-kicker text-coral">Selección del chef</p>
                    <h2 class="section-title">Platos protagonistas</h2>
                </div>
                <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                    @foreach ($featuredRecipes as $recipe)
                        <x-recipe-card :recipe="$recipe" featured />
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <section id="recetas" class="scroll-mt-24 px-5 py-20 lg:px-8">
        <div class="mx-auto max-w-7xl">
            <div class="mb-9 flex flex-col justify-between gap-6 lg:flex-row lg:items-end" data-reveal>
                <div>
                    <p class="section-kicker text-mint">Archivo culinario</p>
                    <h2 class="section-title">¿Qué cocinamos hoy?</h2>
                </div>
                <p class="max-w-sm text-sm leading-6 text-cream/50">Busca por plato, filtra por universo y encuentra una receta a la altura de tu próxima aventura.</p>
            </div>

            <form method="GET" action="{{ route('home') }}#recetas" class="mb-10 grid gap-3 rounded-[2rem] border border-white/10 bg-night/70 p-4 shadow-xl md:grid-cols-[1fr_220px_190px_auto]" data-reveal>
                <label class="relative">
                    <span class="sr-only">Buscar recetas</span>
                    <span class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-cream/35">⌕</span>
                    <input type="search" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Busca arroz, curry, dumplings..." class="form-control pl-11">
                </label>
                <label>
                    <span class="sr-only">Filtrar por anime</span>
                    <select name="anime" class="form-control">
                        <option value="">Todos los universos</option>
                        @foreach ($series as $anime)
                            <option value="{{ $anime->slug }}" @selected(($filters['anime'] ?? '') === $anime->slug)>{{ $anime->name }}</option>
                        @endforeach
                    </select>
                </label>
                <label>
                    <span class="sr-only">Filtrar por dificultad</span>
                    <select name="difficulty" class="form-control">
                        <option value="">Cualquier dificultad</option>
                        @foreach (['fácil', 'media', 'desafiante'] as $difficulty)
                            <option value="{{ $difficulty }}" @selected(($filters['difficulty'] ?? '') === $difficulty)>{{ ucfirst($difficulty) }}</option>
                        @endforeach
                    </select>
                </label>
                <button class="rounded-2xl bg-mint px-6 py-3 text-sm font-black text-ink transition hover:bg-[#75e4c4]">Filtrar</button>
            </form>

            <div class="mb-6 flex items-center justify-between text-sm text-cream/45">
                <p><strong class="text-cream">{{ $recipes->total() }}</strong> resultados encontrados</p>
                @if (array_filter($filters))
                    <a href="{{ route('home') }}#recetas" class="font-bold text-coral hover:text-cream">Limpiar filtros ×</a>
                @endif
            </div>

            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                @forelse ($recipes as $recipe)
                    <x-recipe-card :recipe="$recipe" />
                @empty
                    <div class="rounded-[2rem] border border-dashed border-white/15 bg-white/[0.03] p-12 text-center md:col-span-2 lg:col-span-3">
                        <p class="text-4xl">🍜</p>
                        <h3 class="mt-4 text-xl font-black">No encontramos ese plato</h3>
                        <p class="mt-2 text-sm text-cream/50">Prueba con otro nombre o elimina alguno de los filtros.</p>
                    </div>
                @endforelse
            </div>

            @if ($recipes->hasPages())
                <div class="mt-12">{{ $recipes->links() }}</div>
            @endif
        </div>
    </section>

    <section class="px-5 pb-10 pt-8 lg:px-8">
        <div class="relative mx-auto max-w-7xl overflow-hidden rounded-[2.75rem] border border-mint/25 bg-gradient-to-br from-mint/20 via-night to-lilac/15 p-8 sm:p-12 lg:p-16" data-reveal>
            <div class="speed-lines pointer-events-none absolute -right-20 -top-24 size-96 opacity-20"></div>
            <div class="relative grid items-center gap-10 lg:grid-cols-[1fr_auto]">
                <div>
                    <span class="inline-flex rounded-full border border-mint/30 bg-mint/10 px-4 py-2 text-xs font-black uppercase tracking-[0.2em] text-mint">API lista para conectar</span>
                    <h2 class="mt-5 max-w-3xl text-balance text-3xl font-black tracking-tight sm:text-5xl">Comparte el menú con tu otro proyecto.</h2>
                    <p class="mt-4 max-w-2xl text-base leading-7 text-cream/55">OAuth 2.0 con Laravel Passport, plato diario público, encuesta comunitaria y respuestas JSON consistentes para integrar MeshiVerse de forma segura.</p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('suggestions.create') }}" class="inline-flex items-center justify-center rounded-full border border-white/15 px-7 py-4 text-sm font-black text-cream transition hover:-translate-y-1 hover:border-gold/50">Sugerir platillo</a>
                    <a href="{{ route('api.docs') }}" class="inline-flex items-center justify-center gap-3 rounded-full bg-cream px-7 py-4 text-sm font-black text-ink shadow-xl transition hover:-translate-y-1 hover:bg-gold">Ver documentación <span>↗</span></a>
                </div>
            </div>
        </div>
    </section>
@endsection
