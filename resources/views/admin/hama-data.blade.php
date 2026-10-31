@extends('layouts.dashboard')

@section('title', 'Datos de Hama — MeshiVerse')

@section('content')
    <section class="relative overflow-hidden rounded-[2rem] border border-gold/20 bg-gradient-to-br from-wine/85 via-night to-ink p-7 shadow-2xl shadow-black/20 sm:p-10">
        <div class="dungeon-pattern pointer-events-none absolute inset-0 opacity-20"></div>
        <div class="pointer-events-none absolute -right-20 -top-20 size-72 rounded-full bg-coral/15 blur-3xl"></div>
        <div class="relative flex flex-col gap-7 lg:flex-row lg:items-end lg:justify-between">
            <div class="max-w-3xl">
                <p class="section-kicker text-gold">Integración autenticada</p>
                <h1 class="mt-3 font-display text-4xl font-black tracking-tight text-cream sm:text-6xl">Archivo de <span class="text-gold">Hama</span></h1>
                <p class="mt-4 max-w-2xl text-sm leading-7 text-cream/60 sm:text-base">
                    Esta pantalla consulta directamente <code class="text-mint">GET /api/v1/hamas</code> usando el token cifrado del otro proyecto. Los datos mostrados no son ejemplos locales.
                </p>
            </div>
            @if ($connection)
                <div class="grid min-w-64 gap-2 rounded-2xl border border-white/10 bg-ink/55 p-4 text-sm">
                    <div class="flex items-center justify-between gap-4"><span class="text-cream/45">Estado</span><strong class="text-mint">{{ $connection->last_status === 'connected' ? 'Conectado' : 'Revisar' }}</strong></div>
                    <div class="flex items-center justify-between gap-4"><span class="text-cream/45">Registros</span><strong class="text-cream">{{ $connection->records_count }}</strong></div>
                    <div class="flex items-center justify-between gap-4"><span class="text-cream/45">Servicio</span><strong class="max-w-36 truncate text-cream">{{ $connection->service_name }}</strong></div>
                </div>
            @endif
        </div>
    </section>

    @if ($errors->has('partner_connection'))
        <div class="mt-6 rounded-2xl border border-coral/35 bg-coral/10 px-5 py-4 text-sm font-bold text-coral" role="alert">
            {{ $errors->first('partner_connection') }}
        </div>
    @endif

    @if ($errors->has('hama_creation'))
        <div class="mt-6 rounded-2xl border border-coral/35 bg-coral/10 px-5 py-4 text-sm font-bold text-coral" role="alert">
            {{ $errors->first('hama_creation') }}
        </div>
    @endif

    @if (! $connection)
        <section class="mx-auto mt-8 max-w-3xl rounded-[2rem] border border-gold/20 bg-night/75 p-6 shadow-xl sm:p-9">
            <div class="flex items-start gap-4">
                <span class="grid size-12 shrink-0 place-items-center rounded-2xl bg-gold/15 text-xl font-black text-gold">01</span>
                <div>
                    <p class="section-kicker text-gold">Conectar ProyectoAPI</p>
                    <h2 class="text-3xl font-black text-cream">Pega una vez el token Hama</h2>
                    <p class="mt-3 text-sm leading-6 text-cream/55">El token anterior solo existía en el navegador y no puede recuperarse. Al pegarlo aquí, MeshiVerse verificará el permiso <code class="text-mint">hamas.read</code> y lo guardará cifrado.</p>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.api.connections.test') }}" class="mt-7 space-y-5">
                @csrf
                <input type="hidden" name="save_connection" value="1">
                <div>
                    <label for="partner_url" class="mb-2 block text-xs font-black uppercase tracking-widest text-cream/45">URL de ProyectoAPI</label>
                    <input id="partner_url" name="partner_url" type="url" value="{{ old('partner_url', 'http://127.0.0.1:8000') }}" class="form-control" required>
                    @error('partner_url')<p class="mt-2 text-sm text-coral">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="partner_token" class="mb-2 block text-xs font-black uppercase tracking-widest text-cream/45">Token Bearer generado por Hama</label>
                    <textarea id="partner_token" name="partner_token" rows="5" class="w-full resize-y rounded-2xl border border-white/10 bg-ink/70 px-4 py-3 text-sm text-cream outline-none transition placeholder:text-cream/25 focus:border-gold/60 focus:ring-4 focus:ring-gold/10" placeholder="Pega aquí el token completo" required></textarea>
                    @error('partner_token')<p class="mt-2 text-sm text-coral">{{ $message }}</p>@enderror
                </div>
                <button type="submit" class="inline-flex w-full items-center justify-center rounded-2xl bg-gradient-to-r from-gold to-coral px-5 py-3.5 text-sm font-black text-ink transition hover:-translate-y-0.5 hover:shadow-xl hover:shadow-coral/15">
                    Verificar, guardar y traer los Hama
                </button>
            </form>
        </section>
    @else
        <section class="mt-8 grid gap-5 xl:grid-cols-[1fr_auto]">
            <div class="rounded-[2rem] border {{ $connectionError ? 'border-coral/30 bg-coral/10' : 'border-mint/30 bg-mint/10' }} p-6 sm:p-7">
                @if ($connectionError)
                    <p class="text-xs font-black uppercase tracking-[.2em] text-coral">No se pudo actualizar</p>
                    <h2 class="mt-2 text-2xl font-black text-cream">ProyectoAPI respondió con un problema</h2>
                    <p class="mt-3 text-sm leading-6 text-cream/65">{{ $connectionError }}</p>
                @else
                    <p class="text-xs font-black uppercase tracking-[.2em] text-mint">Transferencia comprobada</p>
                    <h2 class="mt-2 text-2xl font-black text-cream">{{ $hamas->count() }} Hama recibidos desde ProyectoAPI</h2>
                    <p class="mt-3 text-sm leading-6 text-cream/65">
                        El servidor remoto aceptó el Bearer token y devolvió el contrato <code class="text-mint">{{ data_get($meta, 'api_version', 'v1') }}</code>
                        @if (data_get($meta, 'generated_at')) a las {{ Illuminate\Support\Carbon::parse(data_get($meta, 'generated_at'))->translatedFormat('d M Y, H:i:s') }}@endif.
                    </p>
                @endif
                <p class="mt-3 break-all text-xs text-cream/35">{{ $connection->base_url }}/api/v1/hamas</p>
            </div>

            <div class="flex flex-col gap-3 sm:flex-row xl:flex-col">
                <a href="{{ route('admin.api.hamas.index') }}" class="inline-flex items-center justify-center rounded-2xl bg-mint px-5 py-3 text-sm font-black text-ink transition hover:-translate-y-0.5 hover:bg-cream">Actualizar datos</a>
                <form method="POST" action="{{ route('admin.api.hamas.connection.destroy') }}" onsubmit="return confirm('¿Eliminar el token Hama guardado?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex w-full items-center justify-center rounded-2xl border border-coral/30 px-5 py-3 text-sm font-black text-coral transition hover:bg-coral/10">Olvidar conexión</button>
                </form>
            </div>
        </section>

        @if ($canCreateHamas)
            <details class="mt-8 overflow-hidden rounded-[2rem] border border-gold/25 bg-night/75 shadow-xl shadow-black/15" @if ($errors->hasAny(['hama_creation', 'nombre', 'descripcion', 'ancho', 'alto', 'color_principal', 'patron_json'])) open @endif>
                <summary class="flex cursor-pointer list-none flex-col gap-4 p-6 marker:hidden sm:flex-row sm:items-center sm:justify-between sm:p-8">
                    <div>
                        <p class="section-kicker text-gold">Permiso hamas.write confirmado</p>
                        <h2 class="text-3xl font-black text-cream">Crear un Hama en ProyectoAPI</h2>
                        <p class="mt-2 text-sm text-cream/50">Diseña el patrón aquí y MeshiVerse lo enviará por la API autenticada.</p>
                    </div>
                    <span class="rounded-full bg-gold px-5 py-2.5 text-sm font-black text-ink">Abrir diseñador</span>
                </summary>

                <form method="POST" action="{{ route('admin.api.hamas.store') }}" class="border-t border-white/10 p-6 sm:p-8" data-hama-creator>
                    @csrf
                    <div class="grid gap-7 xl:grid-cols-[.72fr_1.28fr]">
                        <div class="space-y-5">
                            <div>
                                <label for="nombre" class="mb-2 block text-xs font-black uppercase tracking-widest text-cream/45">Nombre del Hama</label>
                                <input id="nombre" name="nombre" type="text" value="{{ old('nombre') }}" class="form-control" maxlength="255" required>
                                @error('nombre')<p class="mt-2 text-sm text-coral">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="descripcion" class="mb-2 block text-xs font-black uppercase tracking-widest text-cream/45">Descripción</label>
                                <textarea id="descripcion" name="descripcion" rows="4" class="w-full resize-y rounded-2xl border border-white/10 bg-ink/70 px-4 py-3 text-sm text-cream outline-none transition focus:border-gold/60 focus:ring-4 focus:ring-gold/10" maxlength="5000">{{ old('descripcion') }}</textarea>
                                @error('descripcion')<p class="mt-2 text-sm text-coral">{{ $message }}</p>@enderror
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="ancho" class="mb-2 block text-xs font-black uppercase tracking-widest text-cream/45">Ancho</label>
                                    <input id="ancho" name="ancho" type="number" value="{{ old('ancho', 16) }}" min="1" max="50" class="form-control" data-hama-width required>
                                    @error('ancho')<p class="mt-2 text-sm text-coral">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label for="alto" class="mb-2 block text-xs font-black uppercase tracking-widest text-cream/45">Alto</label>
                                    <input id="alto" name="alto" type="number" value="{{ old('alto', 16) }}" min="1" max="50" class="form-control" data-hama-height required>
                                    @error('alto')<p class="mt-2 text-sm text-coral">{{ $message }}</p>@enderror
                                </div>
                            </div>

                            <div class="rounded-2xl border border-white/10 bg-ink/45 p-4">
                                <label for="color_principal" class="block text-xs font-black uppercase tracking-widest text-cream/45">Color principal</label>
                                <div class="mt-3 flex items-center gap-3">
                                    <input id="color_principal" name="color_principal" type="color" value="{{ old('color_principal', '#D4A017') }}" class="size-12 cursor-pointer rounded-xl border-0 bg-transparent p-0" data-hama-main-color required>
                                    <input type="text" value="{{ old('color_principal', '#D4A017') }}" class="form-control min-w-0 flex-1 uppercase" data-hama-color-text aria-label="Color hexadecimal">
                                </div>
                                @error('color_principal')<p class="mt-2 text-sm text-coral">{{ $message }}</p>@enderror
                            </div>

                            <div class="rounded-2xl border border-mint/20 bg-mint/5 p-4 text-xs leading-5 text-cream/50">
                                El Hama creado desde una integración externa será público en ProyectoAPI. La cantidad de cuentas se calcula automáticamente desde el patrón.
                            </div>
                        </div>

                        <div>
                            <div class="flex flex-wrap items-center justify-between gap-3">
                                <div>
                                    <p class="text-xs font-black uppercase tracking-widest text-gold">Tablero de cuentas</p>
                                    <p class="mt-1 text-xs text-cream/40">Clic para pintar · clic derecho para borrar</p>
                                </div>
                                <div class="flex flex-wrap items-center gap-2">
                                    <label class="flex items-center gap-2 rounded-xl border border-white/10 bg-ink/55 px-3 py-2 text-xs font-bold text-cream">
                                        Pintar con <input type="color" value="{{ old('color_principal', '#D4A017') }}" class="size-7 cursor-pointer rounded border-0 bg-transparent p-0" data-hama-paint-color>
                                    </label>
                                    <button type="button" class="rounded-xl border border-white/10 px-3 py-2 text-xs font-black text-cream transition hover:bg-white/5" data-hama-clear>Limpiar</button>
                                </div>
                            </div>

                            <div class="mt-4 overflow-auto rounded-2xl border border-gold/20 bg-cream p-3 shadow-inner">
                                <canvas width="720" height="720" class="mx-auto block aspect-square w-full min-w-96 cursor-crosshair touch-none rounded-xl bg-white" data-hama-editor aria-label="Editor visual del patrón Hama"></canvas>
                            </div>

                            <div class="mt-4 flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-white/10 bg-ink/45 p-4">
                                <p class="text-sm text-cream/50"><strong class="text-cream" data-hama-count>1</strong> cuentas seleccionadas</p>
                                <p class="text-xs text-cream/35">Máximo 2,500 posiciones</p>
                            </div>

                            <input type="hidden" name="patron_json" value="{{ old('patron_json', '[{&quot;posicion&quot;:0,&quot;color&quot;:&quot;#D4A017&quot;}]') }}" data-hama-pattern>
                            @error('patron_json')<p class="mt-3 text-sm font-bold text-coral">{{ $message }}</p>@enderror

                            <button type="submit" class="mt-5 inline-flex w-full items-center justify-center rounded-2xl bg-gradient-to-r from-gold to-coral px-5 py-4 text-sm font-black text-ink transition hover:-translate-y-0.5 hover:shadow-xl hover:shadow-coral/15">
                                Crear Hama mediante la API
                            </button>
                        </div>
                    </div>
                </form>
            </details>
        @else
            <section class="mt-8 rounded-[2rem] border border-lilac/25 bg-lilac/10 p-6 sm:p-8">
                <p class="text-xs font-black uppercase tracking-[.2em] text-lilac">Creación no habilitada</p>
                <h2 class="mt-2 text-2xl font-black text-cream">El token actual solo permite consultar Hama</h2>
                <p class="mt-3 text-sm leading-6 text-cream/55">Para mostrar el diseñador, genera en ProyectoAPI un token con <code class="text-lilac">hamas.read</code> y <code class="text-lilac">hamas.write</code>, elimina esta conexión y vuelve a pegarlo.</p>
                <a href="{{ route('admin.api.docs') }}" class="mt-5 inline-flex rounded-full border border-lilac/30 px-4 py-2 text-xs font-black text-lilac transition hover:bg-lilac/10">Ir a Configuración API</a>
            </section>
        @endif

        <section class="mt-8 grid gap-6 md:grid-cols-2 2xl:grid-cols-3">
            @forelse ($hamas as $hama)
                @php
                    $hamaId = data_get($hama, 'id', $loop->iteration);
                    $patternId = 'hama-pattern-'.$hamaId.'-'.$loop->index;
                    $pattern = is_array(data_get($hama, 'patron')) ? data_get($hama, 'patron') : [];
                @endphp
                <article class="overflow-hidden rounded-[2rem] border border-white/10 bg-night/75 shadow-xl shadow-black/15" data-reveal>
                    <div class="relative border-b border-white/10 bg-cream p-5">
                        <canvas
                            class="aspect-[4/3] w-full rounded-xl border border-ink/15 bg-white"
                            width="480"
                            height="360"
                            data-hama-board
                            data-pattern-id="{{ $patternId }}"
                            data-board-width="{{ data_get($hama, 'ancho', 1) }}"
                            data-board-height="{{ data_get($hama, 'alto', 1) }}"
                            data-main-color="{{ data_get($hama, 'color_principal', '#d7c4a1') }}"
                            aria-label="Patrón de {{ data_get($hama, 'nombre', 'Hama') }}"
                        ></canvas>
                        <script type="application/json" id="{{ $patternId }}">@json($pattern)</script>
                        <span class="absolute right-7 top-7 rounded-full px-3 py-1 text-[10px] font-black uppercase tracking-widest text-white shadow" style="background-color: {{ data_get($hama, 'color_principal', '#6b3f2a') }}">#{{ $hamaId }}</span>
                    </div>

                    <div class="p-6">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <p class="text-[10px] font-black uppercase tracking-[.2em] text-gold">Proyecto Hama</p>
                                <h3 class="mt-2 text-2xl font-black text-cream">{{ data_get($hama, 'nombre', 'Sin nombre') }}</h3>
                            </div>
                            <span class="rounded-full {{ data_get($hama, 'es_publico') ? 'bg-mint/15 text-mint' : 'bg-lilac/15 text-lilac' }} px-3 py-1 text-[10px] font-black uppercase tracking-widest">
                                {{ data_get($hama, 'es_publico') ? 'Público' : 'Privado' }}
                            </span>
                        </div>

                        <p class="mt-4 text-sm leading-6 text-cream/55">{{ data_get($hama, 'descripcion', 'Sin descripción registrada.') }}</p>

                        <dl class="mt-5 grid grid-cols-2 gap-3 text-sm">
                            <div class="rounded-xl bg-ink/55 p-3"><dt class="text-xs text-cream/35">Dimensiones</dt><dd class="mt-1 font-black text-cream">{{ data_get($hama, 'ancho', 0) }} × {{ data_get($hama, 'alto', 0) }}</dd></div>
                            <div class="rounded-xl bg-ink/55 p-3"><dt class="text-xs text-cream/35">Cuentas</dt><dd class="mt-1 font-black text-cream">{{ data_get($hama, 'cantidad_cuentas', count($pattern)) }}</dd></div>
                            <div class="rounded-xl bg-ink/55 p-3"><dt class="text-xs text-cream/35">Color principal</dt><dd class="mt-1 flex items-center gap-2 font-black text-cream"><span class="size-3 rounded-full" style="background: {{ data_get($hama, 'color_principal', '#ffffff') }}"></span>{{ data_get($hama, 'color_principal', '—') }}</dd></div>
                            <div class="rounded-xl bg-ink/55 p-3"><dt class="text-xs text-cream/35">Puntos del patrón</dt><dd class="mt-1 font-black text-cream">{{ count($pattern) }}</dd></div>
                        </dl>

                        <div class="mt-5 border-t border-white/10 pt-4 text-xs leading-5 text-cream/35">
                            <p>Creado: {{ data_get($hama, 'created_at') ? \Illuminate\Support\Carbon::parse(data_get($hama, 'created_at'))->translatedFormat('d M Y, H:i') : 'Sin fecha' }}</p>
                            <p>Actualizado: {{ data_get($hama, 'updated_at') ? \Illuminate\Support\Carbon::parse(data_get($hama, 'updated_at'))->translatedFormat('d M Y, H:i') : 'Sin fecha' }}</p>
                        </div>

                        <details class="mt-4 rounded-xl border border-white/10 bg-ink/45 p-4">
                            <summary class="cursor-pointer text-xs font-black uppercase tracking-widest text-mint">Ver JSON transferido</summary>
                            <pre class="mt-4 max-h-80 overflow-auto whitespace-pre-wrap break-all text-[11px] leading-5 text-cream/55">{{ json_encode($hama, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) }}</pre>
                        </details>
                    </div>
                </article>
            @empty
                @if (! $connectionError)
                    <div class="md:col-span-2 2xl:col-span-3 rounded-[2rem] border border-dashed border-white/15 bg-night/50 px-6 py-16 text-center">
                        <p class="text-4xl">◇</p>
                        <h2 class="mt-4 text-2xl font-black text-cream">La conexión funciona, pero no hay Hama públicos</h2>
                        <p class="mt-2 text-sm text-cream/45">ProyectoAPI devolvió una lista vacía.</p>
                    </div>
                @endif
            @endforelse
        </section>
    @endif
@endsection
