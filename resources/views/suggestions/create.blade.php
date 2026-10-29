@extends('layouts.app')

@section('title', 'Sugiere un platillo — MeshiVerse')

@section('content')
    <section class="relative overflow-hidden border-b border-white/10 px-5 py-16 lg:px-8 lg:py-24">
        <div class="speed-lines pointer-events-none absolute -right-28 -top-24 size-[36rem] opacity-20"></div>
        <div class="mx-auto grid max-w-7xl items-center gap-12 lg:grid-cols-[.9fr_1.1fr]">
            <div data-reveal>
                <span class="inline-flex rounded-full border border-lilac/30 bg-lilac/10 px-4 py-2 text-xs font-black uppercase tracking-[0.2em] text-lilac">Encuesta de la comunidad</span>
                <h1 class="mt-6 text-balance text-5xl font-black leading-[.94] tracking-[-0.05em] sm:text-7xl">¿Qué platillo debe entrar al <span class="text-gold">próximo menú?</span></h1>
                <p class="mt-6 max-w-xl text-lg leading-8 text-cream/60">Propón una receta de anime o videojuego. La sugerencia queda guardada para revisar futuras ampliaciones del catálogo y también puede enviarse desde la API.</p>
                <div class="mt-9 grid gap-3 sm:grid-cols-3">
                    @foreach ([['📺', 'Anime'], ['🎮', 'Videojuegos'], ['✨', 'Otros mundos']] as [$icon, $label])
                        <div class="rounded-2xl border border-white/10 bg-white/[0.035] p-4"><span class="text-2xl">{{ $icon }}</span><p class="mt-2 text-sm font-black">{{ $label }}</p></div>
                    @endforeach
                </div>
            </div>

            <div class="rounded-[2.5rem] border border-white/10 bg-night/90 p-6 shadow-2xl sm:p-9" data-reveal>
                @if (session('success'))
                    <div class="mb-6 rounded-2xl border border-mint/30 bg-mint/10 p-5 text-sm font-bold leading-6 text-mint">{{ session('success') }}</div>
                @endif

                <form method="POST" action="{{ route('suggestions.store') }}" class="space-y-5">
                    @csrf
                    <div>
                        <label for="dish_name" class="mb-2 block text-sm font-black text-cream">Nombre del platillo</label>
                        <input id="dish_name" name="dish_name" value="{{ old('dish_name') }}" class="form-control" placeholder="Ej. curry de los cazadores" required>
                        @error('dish_name')<p class="mt-2 text-xs font-bold text-coral">{{ $message }}</p>@enderror
                    </div>
                    <div class="grid gap-5 sm:grid-cols-2">
                        <div>
                            <label for="source_title" class="mb-2 block text-sm font-black text-cream">Anime o videojuego</label>
                            <input id="source_title" name="source_title" value="{{ old('source_title') }}" class="form-control" placeholder="Nombre de la obra" required>
                            @error('source_title')<p class="mt-2 text-xs font-bold text-coral">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="source_type" class="mb-2 block text-sm font-black text-cream">Tipo de origen</label>
                            <select id="source_type" name="source_type" class="form-control" required>
                                <option value="">Selecciona uno</option>
                                <option value="anime" @selected(old('source_type') === 'anime')>Anime</option>
                                <option value="videojuego" @selected(old('source_type') === 'videojuego')>Videojuego</option>
                                <option value="otro" @selected(old('source_type') === 'otro')>Otro</option>
                            </select>
                            @error('source_type')<p class="mt-2 text-xs font-bold text-coral">{{ $message }}</p>@enderror
                        </div>
                    </div>
                    <div>
                        <label for="reason" class="mb-2 block text-sm font-black text-cream">¿Por qué debería entrar?</label>
                        <textarea id="reason" name="reason" rows="5" class="form-control h-auto py-4" placeholder="Cuéntanos en qué escena aparece o por qué es memorable...">{{ old('reason') }}</textarea>
                        @error('reason')<p class="mt-2 text-xs font-bold text-coral">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="email" class="mb-2 block text-sm font-black text-cream">Correo opcional</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="Para avisarte si se agrega">
                        @error('email')<p class="mt-2 text-xs font-bold text-coral">{{ $message }}</p>@enderror
                    </div>
                    <button class="w-full rounded-2xl bg-gradient-to-r from-coral to-gold px-6 py-4 text-sm font-black text-ink shadow-xl shadow-coral/10 transition hover:-translate-y-1">Enviar propuesta ✦</button>
                </form>
            </div>
        </div>
    </section>
@endsection
