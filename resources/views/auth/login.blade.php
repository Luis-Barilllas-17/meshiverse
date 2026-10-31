@extends('layouts.app')

@section('title', 'Iniciar sesión — MeshiVerse')

@section('content')
    <section class="relative min-h-[calc(100vh-80px)] overflow-hidden px-5 py-12 lg:px-8 lg:py-20">
        <div class="speed-lines pointer-events-none absolute -right-48 -top-40 size-[42rem] opacity-20"></div>
        <div class="mx-auto grid max-w-6xl overflow-hidden rounded-[2.5rem] border border-gold/20 bg-night/90 shadow-2xl shadow-black/35 lg:grid-cols-[1.05fr_.95fr]" data-reveal>
            <div class="relative min-h-80 overflow-hidden lg:min-h-[42rem]">
                <img src="{{ asset('images/recipes/4af33ee7-1013-4234-b3a6-d28a984936a9.jpg') }}" alt="Banquete inspirado en Delicious in Dungeon" class="absolute inset-0 size-full object-cover opacity-70">
                <div class="absolute inset-0 bg-gradient-to-t from-ink via-wine/45 to-transparent"></div>
                <div class="absolute inset-x-0 bottom-0 p-8 sm:p-10">
                    <span class="rounded-full border border-gold/30 bg-ink/70 px-4 py-2 text-xs font-black uppercase tracking-[.18em] text-gold">Bienvenido al gremio</span>
                    <h1 class="mt-5 max-w-xl font-display text-4xl font-black leading-tight sm:text-6xl">Tu mesa de aventuras te espera.</h1>
                    <p class="mt-4 max-w-lg text-sm leading-7 text-cream/65">Guarda platos, propón nuevas recetas o administra el gran archivo culinario.</p>
                </div>
            </div>

            <div class="flex items-center p-7 sm:p-12">
                <div class="w-full">
                    <p class="section-kicker">Acceso MeshiVerse</p>
                    <h2 class="font-display text-4xl font-black">Iniciar sesión</h2>
                    <p class="mt-3 text-sm leading-6 text-cream/50">El sistema reconocerá automáticamente si entras como administrador o usuario.</p>

                    <form method="POST" action="{{ route('login.store') }}" class="mt-8 space-y-5">
                        @csrf
                        <div>
                            <label for="email" class="form-label">Correo electrónico</label>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-control" autocomplete="email" required autofocus>
                            @error('email')<p class="form-error">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="password" class="form-label">Contraseña</label>
                            <input id="password" type="password" name="password" class="form-control" autocomplete="current-password" required>
                            @error('password')<p class="form-error">{{ $message }}</p>@enderror
                        </div>
                        <label class="flex items-center gap-3 text-sm text-cream/60">
                            <input type="checkbox" name="remember" value="1" class="size-4 rounded border-gold/30 bg-ink text-gold">
                            Mantener mi sesión abierta
                        </label>
                        <button class="primary-button w-full py-3.5">Entrar al recetario</button>
                    </form>

                    <div class="mt-8 grid gap-3 rounded-2xl border border-gold/15 bg-ink/50 p-4 text-xs text-cream/50">
                        <p class="font-black uppercase tracking-widest text-gold">Cuentas locales de prueba</p>
                        <p><strong class="text-cream">Admin:</strong> admin@meshiverse.test · password</p>
                        <p><strong class="text-cream">Usuario:</strong> usuario@meshiverse.test · password</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
