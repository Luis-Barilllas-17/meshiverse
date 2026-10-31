@extends('layouts.app')

@section('title', $recipe->title.' — MeshiVerse')

@section('content')
    <section class="relative min-h-[38rem] overflow-hidden border-b border-white/10">
        <img src="{{ asset($recipe->image_path) }}" alt="Ilustración de {{ $recipe->title }}" class="absolute inset-0 size-full object-cover object-center">
        <div class="absolute inset-0 bg-gradient-to-r from-ink via-ink/85 to-ink/20"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-ink via-transparent to-ink/30"></div>
        <div class="relative mx-auto flex min-h-[38rem] max-w-7xl items-end px-5 py-14 lg:px-8 lg:py-20">
            <div class="max-w-3xl" data-reveal>
                <a href="{{ route('home', ['anime' => $recipe->animeSeries->slug]) }}#recetas" class="mb-6 inline-flex items-center gap-2 rounded-full border border-white/20 bg-ink/70 py-2 pl-2 pr-4 text-xs font-black uppercase tracking-[0.18em] backdrop-blur-md">
                    <img src="{{ asset($recipe->animeSeries->image_path) }}" alt="" class="size-8 rounded-full object-cover" aria-hidden="true">
                    {{ $recipe->animeSeries->name }}
                </a>
                <h1 class="text-balance text-5xl font-black leading-[.95] tracking-[-0.05em] sm:text-6xl lg:text-7xl">{{ $recipe->title }}</h1>
                <p class="mt-5 max-w-2xl text-lg leading-7 text-cream/70">{{ $recipe->subtitle }}</p>
                <div class="mt-8 flex flex-wrap gap-3">
                    <span class="metadata-pill"><strong>{{ $recipe->prep_minutes + $recipe->cook_minutes }}</strong> min totales</span>
                    <span class="metadata-pill"><strong>{{ $recipe->servings }}</strong> porciones</span>
                    <span class="metadata-pill"><strong>{{ ucfirst($recipe->difficulty) }}</strong> dificultad</span>
                    @auth
                        <form method="POST" action="{{ $isFavorite ? route('favorites.destroy', $recipe) : route('favorites.store', $recipe) }}">
                            @csrf
                            @if ($isFavorite)
                                @method('DELETE')
                            @endif
                            <button type="submit" class="metadata-pill transition hover:border-gold/60 hover:text-gold">
                                <strong class="text-gold">{{ $isFavorite ? '★' : '☆' }}</strong>
                                {{ $isFavorite ? 'Guardada en favoritos' : 'Agregar a favoritos' }}
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="metadata-pill transition hover:border-gold/60 hover:text-gold"><strong class="text-gold">☆</strong> Inicia sesión para guardar</a>
                    @endauth
                </div>
            </div>
        </div>
    </section>

    <section class="px-5 py-16 lg:px-8 lg:py-24">
        <div class="mx-auto grid max-w-7xl gap-12 lg:grid-cols-[360px_1fr] xl:gap-20">
            <aside class="lg:sticky lg:top-28 lg:self-start" data-reveal>
                <div class="overflow-hidden rounded-[2rem] border border-white/10 bg-night shadow-2xl">
                    <div class="p-7" style="background: linear-gradient(135deg, {{ $recipe->animeSeries->color_from }}2a, {{ $recipe->animeSeries->color_to }}14)">
                        <p class="text-xs font-black uppercase tracking-[0.2em]" style="color: {{ $recipe->animeSeries->color_from }}">Mise en place</p>
                        <h2 class="mt-2 text-2xl font-black">Ingredientes</h2>
                        <p class="mt-2 text-sm text-cream/50">Marca cada ingrediente mientras preparas tu aventura.</p>
                    </div>
                    <ul class="divide-y divide-white/10 p-4">
                        @foreach ($recipe->ingredients as $ingredient)
                            <li>
                                <label class="group flex cursor-pointer items-start gap-3 rounded-xl px-3 py-3 text-sm leading-6 text-cream/70 transition hover:bg-white/5">
                                    <input type="checkbox" class="peer mt-1 size-4 shrink-0 appearance-none rounded border border-white/25 bg-ink checked:border-mint checked:bg-mint">
                                    <x-ingredient-icon :ingredient="$ingredient" class="transition group-hover:scale-110 peer-checked:opacity-30" />
                                    <span class="transition peer-checked:text-cream/30 peer-checked:line-through">{{ $ingredient }}</span>
                                </label>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="mt-5 rounded-2xl border border-gold/20 bg-gold/10 p-5 text-sm leading-6 text-cream/65">
                    <strong class="block text-xs uppercase tracking-[0.16em] text-gold">Origen</strong>
                    <span class="mt-1 block">{{ $recipe->source_label }} · Adaptación práctica para cocina casera.</span>
                </div>
            </aside>

            <div data-reveal>
                <p class="section-kicker text-coral">La misión culinaria</p>
                <h2 class="section-title">Preparación paso a paso</h2>
                <p class="mt-5 max-w-3xl text-base leading-7 text-cream/60">{{ $recipe->description }}</p>

                <ol class="mt-12 space-y-5">
                    @foreach ($recipe->steps as $step)
                        <li class="group grid gap-5 rounded-[2rem] border border-white/10 bg-white/[0.025] p-6 transition hover:border-white/20 hover:bg-white/[0.045] sm:grid-cols-[64px_1fr] sm:p-8">
                            <span class="grid size-16 place-items-center rounded-2xl text-2xl font-black text-ink shadow-lg transition group-hover:-rotate-3" style="background: linear-gradient(135deg, {{ $recipe->animeSeries->color_from }}, {{ $recipe->animeSeries->color_to }})">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                            <div>
                                <p class="mb-1 text-xs font-black uppercase tracking-[0.18em] text-cream/35">Paso {{ $loop->iteration }}</p>
                                <p class="text-base leading-7 text-cream/75 sm:text-lg">{{ $step }}</p>
                            </div>
                        </li>
                    @endforeach
                </ol>

                <div class="mt-10 flex flex-col justify-between gap-5 rounded-[2rem] border border-mint/20 bg-mint/10 p-7 sm:flex-row sm:items-center">
                    <div>
                        <p class="text-xs font-black uppercase tracking-[0.18em] text-mint">Fin de la misión</p>
                        <p class="mt-2 text-xl font-black">¡El plato está listo para servir!</p>
                    </div>
                    <a href="{{ route('home') }}#recetas" class="rounded-full bg-cream px-6 py-3 text-center text-sm font-black text-ink transition hover:bg-gold">Volver al recetario</a>
                </div>
            </div>
        </div>
    </section>

    @if ($relatedRecipes->isNotEmpty())
        <section class="border-t border-white/10 bg-white/[0.025] px-5 py-20 lg:px-8">
            <div class="mx-auto max-w-7xl">
                <div class="mb-10" data-reveal>
                    <p class="section-kicker">Del mismo universo</p>
                    <h2 class="section-title">Tu próxima receta</h2>
                </div>
                <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                    @foreach ($relatedRecipes as $relatedRecipe)
                        <x-recipe-card :recipe="$relatedRecipe" :is-favorite="$favoriteRecipeIds->contains($relatedRecipe->id)" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection
