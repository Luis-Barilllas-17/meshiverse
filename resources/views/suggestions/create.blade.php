@extends('layouts.dashboard')

@section('title', 'Sugerir receta — MeshiVerse')

@section('content')
    <div class="grid max-w-6xl gap-8 xl:grid-cols-[.8fr_1.2fr]">
        <div>
            <p class="section-kicker">Encuesta de la comunidad</p>
            <h1 class="section-title">Sugiere el próximo platillo</h1>
            <p class="mt-5 text-base leading-7 text-cream/55">Propón una receta de anime o videojuego. El administrador podrá revisarla y convertirla en una ficha completa del recetario.</p>
            <div class="mt-7 grid gap-3">
                @foreach ([['Anime', 'Escenas, mangas y series memorables.'], ['Videojuego', 'Banquetes, campamentos y tabernas.'], ['Otro', 'Cualquier mundo con una historia culinaria.']] as [$label, $description])
                    <div class="rounded-2xl border border-gold/15 bg-night/60 p-5"><p class="font-black text-gold">{{ $label }}</p><p class="mt-1 text-sm text-cream/45">{{ $description }}</p></div>
                @endforeach
            </div>
        </div>

        <form method="POST" action="{{ route('suggestions.store') }}" class="dashboard-card space-y-5">
            @csrf
            <div><label for="dish_name" class="form-label">Nombre del platillo</label><input id="dish_name" name="dish_name" value="{{ old('dish_name') }}" class="form-control" placeholder="Ej. curry de los cazadores" required>@error('dish_name')<p class="form-error">{{ $message }}</p>@enderror</div>
            <div class="grid gap-5 sm:grid-cols-2">
                <div><label for="source_title" class="form-label">Anime o videojuego</label><input id="source_title" name="source_title" value="{{ old('source_title') }}" class="form-control" placeholder="Nombre de la obra" required>@error('source_title')<p class="form-error">{{ $message }}</p>@enderror</div>
                <div><label for="source_type" class="form-label">Tipo de origen</label><select id="source_type" name="source_type" class="form-control" required><option value="">Selecciona uno</option><option value="anime" @selected(old('source_type') === 'anime')>Anime</option><option value="videojuego" @selected(old('source_type') === 'videojuego')>Videojuego</option><option value="otro" @selected(old('source_type') === 'otro')>Otro</option></select>@error('source_type')<p class="form-error">{{ $message }}</p>@enderror</div>
            </div>
            <div><label for="reason" class="form-label">¿Por qué debería entrar?</label><textarea id="reason" name="reason" rows="6" class="form-control h-auto py-4" placeholder="Cuéntanos en qué escena aparece o por qué es memorable...">{{ old('reason') }}</textarea>@error('reason')<p class="form-error">{{ $message }}</p>@enderror</div>
            <div><label for="email" class="form-label">Correo de contacto</label><input id="email" type="email" name="email" value="{{ old('email', auth()->user()->email) }}" class="form-control">@error('email')<p class="form-error">{{ $message }}</p>@enderror</div>
            <button class="primary-button w-full py-3.5">Enviar propuesta ✦</button>
        </form>
    </div>
@endsection
