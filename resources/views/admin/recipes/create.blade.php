@extends('layouts.dashboard')

@section('title', 'Crear receta — MeshiVerse')

@section('content')
    <div class="max-w-5xl">
        <p class="section-kicker">Nuevo registro culinario</p>
        <h1 class="section-title">Crear receta</h1>
        <p class="mt-3 max-w-2xl text-sm leading-6 text-cream/50">Registra todos los datos que aparecerán en la ficha pública: universo, historia, imagen, ingredientes, pasos, tiempos y dificultad.</p>

        @if ($errors->any())
            <div class="mt-6 rounded-xl border border-coral/35 bg-coral/10 px-5 py-4 text-sm text-coral"><strong>Revisa el formulario.</strong> Hay datos incompletos o inválidos.</div>
        @endif

        <form method="POST" action="{{ route('admin.recipes.store') }}" enctype="multipart/form-data" class="mt-8 space-y-7">
            @csrf
            <section class="dashboard-card">
                <div class="mb-6"><p class="section-kicker mb-1">01 · Identidad</p><h2 class="text-2xl font-black">Datos principales</h2></div>
                <div class="grid gap-5 sm:grid-cols-2">
                    <div><label for="anime_series_id" class="form-label">Anime o videojuego</label><select id="anime_series_id" name="anime_series_id" class="form-control" required><option value="">Selecciona un universo</option>@foreach ($series as $anime)<option value="{{ $anime->id }}" @selected(old('anime_series_id') == $anime->id)>{{ $anime->name }}</option>@endforeach</select>@error('anime_series_id')<p class="form-error">{{ $message }}</p>@enderror</div>
                    <div><label for="source_label" class="form-label">Origen o escena</label><input id="source_label" name="source_label" value="{{ old('source_label') }}" class="form-control" placeholder="Ej. Receta de Sanji · Vol. 5" required>@error('source_label')<p class="form-error">{{ $message }}</p>@enderror</div>
                    <div><label for="title" class="form-label">Nombre del platillo</label><input id="title" name="title" value="{{ old('title') }}" class="form-control" required>@error('title')<p class="form-error">{{ $message }}</p>@enderror</div>
                    <div><label for="slug" class="form-label">Slug opcional</label><input id="slug" name="slug" value="{{ old('slug') }}" class="form-control" placeholder="Se genera automáticamente">@error('slug')<p class="form-error">{{ $message }}</p>@enderror</div>
                    <div class="sm:col-span-2"><label for="subtitle" class="form-label">Subtítulo</label><input id="subtitle" name="subtitle" value="{{ old('subtitle') }}" class="form-control" placeholder="Una frase breve y atractiva">@error('subtitle')<p class="form-error">{{ $message }}</p>@enderror</div>
                    <div class="sm:col-span-2"><label for="description" class="form-label">Descripción</label><textarea id="description" name="description" rows="5" class="form-control h-auto py-4" required>{{ old('description') }}</textarea>@error('description')<p class="form-error">{{ $message }}</p>@enderror</div>
                    <div class="sm:col-span-2"><label for="image" class="form-label">Imagen de portada</label><input id="image" type="file" name="image" accept="image/png,image/jpeg,image/webp" class="form-control h-auto py-3 file:mr-4 file:rounded-lg file:border-0 file:bg-gold file:px-4 file:py-2 file:font-black file:text-ink" required><p class="mt-2 text-xs text-cream/35">JPG, PNG o WEBP · máximo 5 MB.</p>@error('image')<p class="form-error">{{ $message }}</p>@enderror</div>
                </div>
            </section>

            <section class="dashboard-card">
                <div class="flex items-center justify-between gap-4"><div><p class="section-kicker mb-1">02 · Mise en place</p><h2 class="text-2xl font-black">Ingredientes</h2></div><button type="button" class="secondary-button" data-add-list="ingredients">＋ Agregar</button></div>
                <div class="mt-6 space-y-3" data-list="ingredients">
                    @foreach (old('ingredients', ['', '', '']) as $ingredient)
                        <div class="flex gap-3" data-list-row><input name="ingredients[]" value="{{ $ingredient }}" class="form-control" placeholder="Ej. 2 tazas de arroz" required><button type="button" class="rounded-xl border border-coral/25 px-4 text-coral" data-remove-row aria-label="Eliminar ingrediente">×</button></div>
                    @endforeach
                </div>
                @error('ingredients')<p class="form-error">{{ $message }}</p>@enderror
                @error('ingredients.*')<p class="form-error">{{ $message }}</p>@enderror
            </section>

            <section class="dashboard-card">
                <div class="flex items-center justify-between gap-4"><div><p class="section-kicker mb-1">03 · Preparación</p><h2 class="text-2xl font-black">Pasos</h2></div><button type="button" class="secondary-button" data-add-list="steps">＋ Agregar</button></div>
                <div class="mt-6 space-y-3" data-list="steps">
                    @foreach (old('steps', ['', '', '']) as $step)
                        <div class="flex gap-3" data-list-row><textarea name="steps[]" rows="3" class="form-control h-auto py-3" placeholder="Describe este paso con claridad" required>{{ $step }}</textarea><button type="button" class="rounded-xl border border-coral/25 px-4 text-coral" data-remove-row aria-label="Eliminar paso">×</button></div>
                    @endforeach
                </div>
                @error('steps')<p class="form-error">{{ $message }}</p>@enderror
                @error('steps.*')<p class="form-error">{{ $message }}</p>@enderror
            </section>

            <section class="dashboard-card">
                <div class="mb-6"><p class="section-kicker mb-1">04 · Servicio</p><h2 class="text-2xl font-black">Tiempos y publicación</h2></div>
                <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
                    <div><label for="servings" class="form-label">Porciones</label><input id="servings" type="number" name="servings" min="1" value="{{ old('servings', 4) }}" class="form-control" required>@error('servings')<p class="form-error">{{ $message }}</p>@enderror</div>
                    <div><label for="prep_minutes" class="form-label">Preparación · min</label><input id="prep_minutes" type="number" name="prep_minutes" min="0" value="{{ old('prep_minutes', 20) }}" class="form-control" required>@error('prep_minutes')<p class="form-error">{{ $message }}</p>@enderror</div>
                    <div><label for="cook_minutes" class="form-label">Cocción · min</label><input id="cook_minutes" type="number" name="cook_minutes" min="0" value="{{ old('cook_minutes', 30) }}" class="form-control" required>@error('cook_minutes')<p class="form-error">{{ $message }}</p>@enderror</div>
                    <div><label for="difficulty" class="form-label">Dificultad</label><select id="difficulty" name="difficulty" class="form-control" required>@foreach (['fácil', 'media', 'desafiante'] as $difficulty)<option value="{{ $difficulty }}" @selected(old('difficulty', 'media') === $difficulty)>{{ ucfirst($difficulty) }}</option>@endforeach</select>@error('difficulty')<p class="form-error">{{ $message }}</p>@enderror</div>
                </div>
                <div class="mt-6 flex flex-wrap gap-5 rounded-xl border border-gold/10 bg-ink/45 p-4">
                    <label class="flex items-center gap-3 text-sm font-bold"><input type="checkbox" name="is_featured" value="1" @checked(old('is_featured')) class="size-4 rounded border-gold/30 bg-ink"> Marcar como destacada</label>
                    <label class="flex items-center gap-3 text-sm font-bold"><input type="checkbox" name="publish_now" value="1" @checked(old('publish_now', true)) class="size-4 rounded border-gold/30 bg-ink"> Publicar inmediatamente</label>
                </div>
            </section>

            <div class="flex flex-wrap justify-end gap-3"><a href="{{ route('admin.dashboard') }}" class="secondary-button">Cancelar</a><button class="primary-button px-8">Guardar receta</button></div>
        </form>
    </div>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('[data-add-list]').forEach((button) => {
        button.addEventListener('click', () => {
            const name = button.dataset.addList;
            const list = document.querySelector(`[data-list="${name}"]`);
            const row = list?.querySelector('[data-list-row]')?.cloneNode(true);
            if (!row) return;
            row.querySelectorAll('input, textarea').forEach((field) => field.value = '');
            list.appendChild(row);
        });
    });
    document.addEventListener('click', (event) => {
        const button = event.target.closest('[data-remove-row]');
        if (!button) return;
        const list = button.closest('[data-list]');
        if (list.querySelectorAll('[data-list-row]').length > 1) button.closest('[data-list-row]').remove();
    });
</script>
@endpush
