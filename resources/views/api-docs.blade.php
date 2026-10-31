@extends('layouts.dashboard')

@section('title', 'Centro de conexión — MeshiVerse')

@section('content')
    <section class="relative overflow-hidden border-b border-white/10 px-5 py-16 lg:px-8 lg:py-24">
        <div class="speed-lines pointer-events-none absolute -right-32 -top-36 size-[40rem] opacity-20"></div>
        <div class="pointer-events-none absolute left-1/3 top-10 size-72 rounded-full bg-mint/10 blur-3xl"></div>

        <div class="mx-auto max-w-7xl">
            <div class="grid items-center gap-12 lg:grid-cols-[1.05fr_.95fr]">
                <div data-reveal>
                    <span class="inline-flex items-center gap-2 rounded-full border border-mint/25 bg-mint/10 px-4 py-2 text-xs font-black uppercase tracking-[0.2em] text-mint">
                        <span class="relative flex size-2">
                            <span class="absolute inline-flex size-full animate-ping rounded-full bg-mint opacity-70"></span>
                            <span class="relative inline-flex size-2 rounded-full bg-mint"></span>
                        </span>
                        Centro de conexión local
                    </span>
                    <h1 class="mt-7 max-w-4xl text-balance text-5xl font-black leading-[.95] tracking-[-0.055em] sm:text-7xl">
                        Conecta dos mundos, <span class="text-gold">comparte el menú.</span>
                    </h1>
                    <p class="mt-6 max-w-2xl text-lg leading-8 text-cream/60">
                        Genera el token que usarán en el otro proyecto y prueba aquí el token que ese proyecto te entregue. MeshiVerse solo habilita el platillo del día y la encuesta de propuestas.
                    </p>
                </div>

                <div class="relative" data-reveal>
                    <div class="absolute inset-8 rounded-full bg-coral/15 blur-3xl"></div>
                    <div class="relative rounded-[2rem] border border-white/10 bg-night/85 p-6 shadow-2xl shadow-black/30 sm:p-8">
                        <div class="grid grid-cols-[1fr_auto_1fr] items-center gap-3">
                            <div class="rounded-2xl border border-gold/25 bg-gold/10 p-5 text-center">
                                <span class="mx-auto grid size-12 place-items-center rounded-2xl bg-gold text-xl font-black text-ink">M</span>
                                <p class="mt-3 font-black text-cream">MeshiVerse</p>
                                <p class="mt-1 text-xs text-cream/45">Puerto 8001</p>
                            </div>
                            <div class="flex flex-col items-center gap-2 text-mint">
                                <span class="text-xl">⇄</span>
                                <span class="rounded-full border border-mint/25 bg-mint/10 px-2.5 py-1 text-[9px] font-black uppercase tracking-widest">Bearer</span>
                            </div>
                            <div class="rounded-2xl border border-lilac/25 bg-lilac/10 p-5 text-center">
                                <span class="mx-auto grid size-12 place-items-center rounded-2xl bg-lilac text-xl font-black text-white">H</span>
                                <p class="mt-3 font-black text-cream">Otro proyecto</p>
                                <p class="mt-1 text-xs text-cream/45">API asociada</p>
                            </div>
                        </div>
                        <div class="mt-5 grid gap-3 sm:grid-cols-2">
                            <div class="rounded-xl border border-white/10 bg-ink/60 p-4">
                                <p class="text-[10px] font-black uppercase tracking-widest text-gold">MeshiVerse envía</p>
                                <p class="mt-2 text-sm font-bold text-cream">Platillo del día</p>
                            </div>
                            <div class="rounded-xl border border-white/10 bg-ink/60 p-4">
                                <p class="text-[10px] font-black uppercase tracking-widest text-mint">MeshiVerse recibe</p>
                                <p class="mt-2 text-sm font-bold text-cream">Propuestas de comida</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="px-5 py-10 lg:px-8">
        <div class="mx-auto max-w-7xl space-y-5">
            @if (session('success'))
                <div class="rounded-2xl border border-mint/30 bg-mint/10 px-5 py-4 text-sm font-bold text-mint" role="status">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('generated_token'))
                <div class="overflow-hidden rounded-[2rem] border border-gold/35 bg-gradient-to-br from-gold/15 to-coral/5 shadow-2xl shadow-gold/5" data-reveal>
                    <div class="flex flex-wrap items-center justify-between gap-3 border-b border-gold/20 px-6 py-5">
                        <div>
                            <p class="text-xs font-black uppercase tracking-[0.2em] text-gold">Token recién generado</p>
                            <p class="mt-1 text-sm text-cream/55">Cópialo y guárdalo en el archivo <code class="text-cream">.env</code> del otro proyecto.</p>
                        </div>
                        <button type="button" data-copy-target="generated-token" class="rounded-full bg-gold px-5 py-2.5 text-sm font-black text-ink transition hover:-translate-y-0.5 hover:bg-cream">Copiar token</button>
                    </div>
                    <pre class="overflow-x-auto whitespace-pre-wrap break-all px-6 py-5 text-sm leading-7 text-cream/75"><code id="generated-token">{{ session('generated_token') }}</code></pre>
                    <div class="border-t border-gold/15 px-6 py-3 text-xs text-cream/40">
                        Expira: {{ \Illuminate\Support\Carbon::parse(session('generated_token_expires_at'))->translatedFormat('d M Y, H:i') }} · No volverá a mostrarse.
                    </div>
                </div>
            @endif

            @if ($errors->has('partner_connection'))
                <div class="rounded-2xl border border-coral/30 bg-coral/10 px-5 py-4 text-sm font-bold text-coral" role="alert">
                    {{ $errors->first('partner_connection') }}
                </div>
            @endif

            @if (session('connection_result'))
                @php($connection = session('connection_result'))
                <div class="rounded-[2rem] border border-mint/35 bg-gradient-to-r from-mint/15 via-mint/5 to-transparent p-6 sm:p-8" data-reveal role="status">
                    <div class="flex flex-col gap-5 sm:flex-row sm:items-start sm:justify-between">
                        <div>
                            <span class="inline-flex items-center gap-2 rounded-full bg-mint px-3 py-1.5 text-[10px] font-black uppercase tracking-widest text-ink">
                                <span class="size-1.5 rounded-full bg-ink"></span> Conexión exitosa
                            </span>
                            <h2 class="mt-4 text-3xl font-black text-cream">{{ $connection['service'] }}</h2>
                            <p class="mt-2 max-w-2xl text-sm leading-6 text-cream/60">{{ $connection['message'] }}</p>
                            <p class="mt-3 text-xs text-cream/35">{{ $connection['base_url'] }}</p>
                        </div>
                        <div class="rounded-2xl border border-white/10 bg-ink/55 px-5 py-4 sm:min-w-64">
                            <p class="text-[10px] font-black uppercase tracking-widest text-mint">Datos confirmados</p>
                            @foreach ($connection['sharing'] as $sharedItem)
                                <p class="mt-2 flex items-center gap-2 text-sm font-bold text-cream"><span class="text-mint">✓</span> {{ $sharedItem }}</p>
                            @endforeach
                            @if ($connection['dish_title'])
                                <p class="mt-3 border-t border-white/10 pt-3 text-xs text-cream/45">Vista previa: {{ $connection['dish_title'] }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>

    <section class="px-5 pb-20 lg:px-8">
        <div class="mx-auto grid max-w-7xl gap-7 xl:grid-cols-2">
            <article class="rounded-[2rem] border border-white/10 bg-night/70 p-6 shadow-xl shadow-black/10 sm:p-8" data-reveal>
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="section-kicker text-gold">Paso 1 · Dar acceso</p>
                        <h2 class="text-3xl font-black tracking-tight text-cream">Generar token de MeshiVerse</h2>
                    </div>
                    <span class="grid size-12 shrink-0 place-items-center rounded-2xl bg-gold/15 text-xl font-black text-gold">01</span>
                </div>
                <p class="mt-4 text-sm leading-6 text-cream/55">Este token se pega en el otro proyecto. Le permitirá comprobar la conexión, leer el platillo diario y enviar respuestas de la encuesta.</p>

                <form method="POST" action="{{ route('admin.api.tokens.store') }}" class="mt-7 space-y-4">
                    @csrf
                    <div>
                        <label for="token_name" class="mb-2 block text-xs font-black uppercase tracking-widest text-cream/45">Nombre del proyecto receptor</label>
                        <input id="token_name" name="token_name" type="text" value="{{ old('token_name', 'Conexión con Hama') }}" class="form-control" maxlength="80" required>
                        @error('token_name')<p class="mt-2 text-sm text-coral">{{ $message }}</p>@enderror
                    </div>
                    <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-gold to-coral px-5 py-3.5 text-sm font-black text-ink transition hover:-translate-y-0.5 hover:shadow-xl hover:shadow-coral/15">
                        Generar token seguro
                    </button>
                </form>

                <div class="mt-7 border-t border-white/10 pt-6">
                    <div class="flex items-center justify-between gap-4">
                        <h3 class="text-sm font-black uppercase tracking-widest text-cream/50">Tokens activos</h3>
                        <span class="rounded-full bg-white/5 px-3 py-1 text-xs font-bold text-cream/40">{{ $activeTokens->count() }}</span>
                    </div>

                    <div class="mt-4 space-y-3">
                        @forelse ($activeTokens as $token)
                            <div class="flex items-center justify-between gap-4 rounded-2xl border border-white/10 bg-ink/55 p-4">
                                <div class="min-w-0">
                                    <p class="truncate text-sm font-bold text-cream">{{ $token->name }}</p>
                                    <p class="mt-1 text-xs text-cream/35">Expira {{ $token->expires_at?->diffForHumans() }}</p>
                                </div>
                                <form method="POST" action="{{ route('admin.api.tokens.destroy', $token->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="rounded-xl border border-coral/25 px-3 py-2 text-xs font-black text-coral transition hover:bg-coral/10">Revocar</button>
                                </form>
                            </div>
                        @empty
                            <div class="rounded-2xl border border-dashed border-white/10 px-4 py-6 text-center text-sm text-cream/35">Todavía no hay tokens de conexión activos.</div>
                        @endforelse
                    </div>
                </div>
            </article>

            <article class="rounded-[2rem] border border-white/10 bg-night/70 p-6 shadow-xl shadow-black/10 sm:p-8" data-reveal>
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="section-kicker text-mint">Paso 2 · Recibir acceso</p>
                        <h2 class="text-3xl font-black tracking-tight text-cream">Probar token del otro proyecto</h2>
                    </div>
                    <span class="grid size-12 shrink-0 place-items-center rounded-2xl bg-mint/15 text-xl font-black text-mint">02</span>
                </div>
                <p class="mt-4 text-sm leading-6 text-cream/55">Pega la URL base y el token generado por el otro proyecto. MeshiVerse hará una solicitud autenticada y mostrará una confirmación clara.</p>

                <form method="POST" action="{{ route('admin.api.connections.test') }}" class="mt-7 space-y-4">
                    @csrf
                    <input type="hidden" name="save_connection" value="0">
                    <div>
                        <label for="partner_url" class="mb-2 block text-xs font-black uppercase tracking-widest text-cream/45">URL del otro proyecto</label>
                        <input id="partner_url" name="partner_url" type="url" value="{{ old('partner_url', 'http://127.0.0.1:8000') }}" class="form-control" placeholder="http://127.0.0.1:8000" required>
                        @error('partner_url')<p class="mt-2 text-sm text-coral">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="partner_token" class="mb-2 block text-xs font-black uppercase tracking-widest text-cream/45">Token entregado por el otro proyecto</label>
                        <textarea id="partner_token" name="partner_token" rows="5" class="w-full resize-y rounded-2xl border border-white/10 bg-ink/70 px-4 py-3 text-sm text-cream outline-none transition placeholder:text-cream/25 focus:border-mint/60 focus:ring-4 focus:ring-mint/10" placeholder="Pega aquí el token Bearer" required></textarea>
                        @error('partner_token')<p class="mt-2 text-sm text-coral">{{ $message }}</p>@enderror
                    </div>
                    <label class="flex cursor-pointer items-start gap-3 rounded-2xl border border-mint/20 bg-mint/5 p-4">
                        <input type="checkbox" name="save_connection" value="1" checked class="mt-0.5 size-4 rounded border-white/20 bg-ink text-mint focus:ring-mint/30">
                        <span>
                            <strong class="block text-sm text-cream">Guardar conexión Hama</strong>
                            <span class="mt-1 block text-xs leading-5 text-cream/45">Si el token incluye <code class="text-mint">hamas.read</code>, se cifrará para mostrar y actualizar todos los Hama desde el panel.</span>
                        </span>
                    </label>
                    <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-mint px-5 py-3.5 text-sm font-black text-ink transition hover:-translate-y-0.5 hover:bg-cream hover:shadow-xl hover:shadow-mint/10">
                        Probar conexión ahora
                    </button>
                </form>

                <div class="mt-6 rounded-2xl border border-white/10 bg-ink/45 p-4 text-xs leading-5 text-cream/40">
                    MeshiVerse reconoce el handshake de Hama en <code class="text-mint">POST /api/v1/connections/handshake</code> y también su propio contrato en <code class="text-mint">GET /api/v1/partner/connection</code>. Solo se guarda el token Hama si marcas la opción anterior; queda cifrado y nunca se muestra otra vez.
                </div>
            </article>
        </div>

        @if ($hamaConnection)
            <a href="{{ route('admin.api.hamas.index') }}" class="mx-auto mt-7 flex max-w-7xl flex-col gap-4 rounded-[2rem] border border-gold/25 bg-gradient-to-r from-gold/15 via-night/75 to-mint/10 p-6 transition hover:-translate-y-0.5 hover:border-gold/45 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-xs font-black uppercase tracking-[.2em] text-gold">Conexión Hama guardada</p>
                    <h3 class="mt-2 text-2xl font-black text-cream">Ver todos los datos transferidos</h3>
                    <p class="mt-2 text-sm text-cream/50">{{ $hamaConnection->service_name }} · {{ $hamaConnection->records_count }} registros en la última comprobación</p>
                </div>
                <span class="rounded-full bg-gold px-5 py-2.5 text-sm font-black text-ink">Abrir visor →</span>
            </a>
        @endif
    </section>

    <section class="border-y border-white/10 bg-white/[0.02] px-5 py-20 lg:px-8">
        <div class="mx-auto max-w-7xl">
            <div class="max-w-3xl" data-reveal>
                <p class="section-kicker text-lilac">Contrato de intercambio</p>
                <h2 class="section-title">Solo se comparten dos experiencias</h2>
                <p class="docs-copy">Los tokens generados desde este centro tienen permisos mínimos. No permiten editar ni eliminar recetas del catálogo.</p>
            </div>

            <div class="mt-10 grid gap-7 lg:grid-cols-2">
                <article class="group overflow-hidden rounded-[2rem] border border-gold/20 bg-night/70" data-reveal>
                    <div class="relative h-64 overflow-hidden">
                        @if ($dishOfTheDay)
                            <img src="{{ asset($dishOfTheDay->image_path) }}" alt="{{ $dishOfTheDay->title }}" class="size-full object-cover transition duration-700 group-hover:scale-105">
                        @else
                            <div class="grid size-full place-items-center bg-gold/10 text-gold">Sin receta publicada</div>
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-night via-night/20 to-transparent"></div>
                        <span class="absolute left-5 top-5 rounded-full bg-gold px-3 py-1.5 text-[10px] font-black uppercase tracking-widest text-ink">GET · salida</span>
                    </div>
                    <div class="p-6 sm:p-8">
                        <p class="text-xs font-black uppercase tracking-widest text-gold">Platillo del día</p>
                        <h3 class="mt-3 text-2xl font-black text-cream">{{ $dishOfTheDay?->title ?? 'Esperando recetas' }}</h3>
                        <p class="mt-3 text-sm leading-6 text-cream/55">El proyecto conectado puede leer una receta estable durante todo el día, con imagen, anime o juego, ingredientes, pasos y tiempos.</p>
                        <div class="mt-5 rounded-xl bg-ink/65 px-4 py-3 font-mono text-xs text-cream/50">GET /api/v1/partner/dish-of-the-day</div>
                    </div>
                </article>

                <article class="overflow-hidden rounded-[2rem] border border-mint/20 bg-night/70" data-reveal>
                    <div class="relative grid h-64 place-items-center overflow-hidden bg-gradient-to-br from-mint/20 via-lilac/10 to-coral/15 p-8">
                        <div class="absolute inset-0 manga-grid opacity-30"></div>
                        <div class="relative w-full max-w-sm rotate-[-2deg] rounded-2xl border border-white/15 bg-cream p-5 text-ink shadow-2xl">
                            <p class="text-[10px] font-black uppercase tracking-widest text-coral">Encuesta de la comunidad</p>
                            <p class="mt-3 text-xl font-black">¿Qué platillo de anime o videojuego agregamos después?</p>
                            <div class="mt-4 h-2 rounded-full bg-ink/10"><div class="h-2 w-2/3 rounded-full bg-mint"></div></div>
                        </div>
                        <span class="absolute left-5 top-5 rounded-full bg-mint px-3 py-1.5 text-[10px] font-black uppercase tracking-widest text-ink">POST · entrada</span>
                    </div>
                    <div class="p-6 sm:p-8">
                        <p class="text-xs font-black uppercase tracking-widest text-mint">Propuestas de comida</p>
                        <h3 class="mt-3 text-2xl font-black text-cream">Anime, juegos y razones</h3>
                        <p class="mt-3 text-sm leading-6 text-cream/55">El otro proyecto puede enviar el nombre del platillo, su serie o videojuego de origen y la razón para agregarlo al recetario.</p>
                        <div class="mt-5 rounded-xl bg-ink/65 px-4 py-3 font-mono text-xs text-cream/50">POST /api/v1/partner/suggestions</div>
                    </div>
                </article>
            </div>
        </div>
    </section>

    <section class="px-5 py-20 lg:px-8">
        <div class="mx-auto max-w-7xl">
            <div class="grid gap-8 lg:grid-cols-[.8fr_1.2fr] lg:items-start">
                <div data-reveal>
                    <p class="section-kicker text-coral">Cómo funciona</p>
                    <h2 class="section-title">Un token por dirección</h2>
                    <div class="mt-7 space-y-5 text-sm leading-6 text-cream/55">
                        <p><strong class="text-cream">Token de MeshiVerse:</strong> se genera arriba y se configura en el otro proyecto para que pueda consumir esta API.</p>
                        <p><strong class="text-cream">Token del otro proyecto:</strong> se pega temporalmente en la prueba para comprobar la API remota. Cada proyecto valida únicamente los tokens que él mismo emitió.</p>
                        <p><strong class="text-cream">Confirmación:</strong> el handshake devuelve el nombre del servicio, sus permisos, los endpoints compartidos y una vista previa del platillo diario.</p>
                    </div>
                </div>

                <div class="code-window" data-reveal>
                    <div class="code-toolbar"><span>Respuesta esperada del handshake</span><button type="button" data-copy-target="handshake-example">Copiar</button></div>
                    <pre id="handshake-example"><code>{
  "status": "connected",
  "service": "MeshiVerse",
  "message": "Conexión autenticada correctamente",
  "sharing": {
    "dish_of_the_day": {
      "method": "GET",
      "scope": "dish-of-the-day:read"
    },
    "recipe_suggestions": {
      "method": "POST",
      "scope": "suggestions:create"
    }
  }
}</code></pre>
                </div>
            </div>
        </div>
    </section>
@endsection
