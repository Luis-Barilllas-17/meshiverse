<!DOCTYPE html>
<html lang="es" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#0b1020">
    <meta name="description" content="Recetas inspiradas en la comida más memorable del anime.">
    <title>@yield('title', 'MeshiVerse — Recetas de anime')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="min-h-screen bg-ink font-sans text-cream antialiased">
    <a href="#contenido" class="sr-only z-[100] rounded-full bg-cream px-5 py-3 text-ink focus:not-sr-only focus:fixed focus:left-4 focus:top-4">Saltar al contenido</a>

    <div class="pointer-events-none fixed inset-0 -z-10 overflow-hidden" aria-hidden="true">
        <div class="absolute -left-24 top-20 size-96 rounded-full bg-coral/10 blur-3xl"></div>
        <div class="absolute -right-24 top-1/3 size-[28rem] rounded-full bg-mint/10 blur-3xl"></div>
        <div class="manga-grid absolute inset-0 opacity-30"></div>
    </div>

    <header class="sticky top-0 z-50 border-b border-white/10 bg-ink/85 backdrop-blur-xl">
        <nav class="mx-auto flex max-w-7xl items-center justify-between px-5 py-4 lg:px-8" aria-label="Navegación principal">
            <a href="{{ route('home') }}" class="group flex items-center gap-3">
                <span class="grid size-11 rotate-3 place-items-center rounded-2xl bg-gradient-to-br from-coral to-gold text-xl font-black text-ink shadow-lg shadow-coral/20 transition group-hover:-rotate-3">M</span>
                <span>
                    <span class="block text-lg font-extrabold tracking-tight">Meshi<span class="text-gold">Verse</span></span>
                    <span class="block text-[10px] font-bold uppercase tracking-[0.25em] text-cream/45">Anime Food Archive</span>
                </span>
            </a>

            <div class="hidden items-center gap-2 md:flex">
                <a href="{{ route('home') }}#universos" class="nav-link">Universos</a>
                <a href="{{ route('home') }}#recetas" class="nav-link">Recetas</a>
                <a href="{{ route('suggestions.create') }}" class="nav-link">Sugerir</a>
                <a href="{{ route('api.docs') }}" class="nav-link">API</a>
                <a href="{{ route('home') }}#recetas" class="ml-3 rounded-full bg-cream px-5 py-2.5 text-sm font-extrabold text-ink transition hover:-translate-y-0.5 hover:bg-gold">Explorar menú</a>
            </div>

            <button type="button" class="grid size-11 place-items-center rounded-xl border border-white/15 text-cream md:hidden" data-menu-toggle aria-controls="mobile-menu" aria-expanded="false">
                <span class="sr-only">Abrir menú</span>
                <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M4 7h16M4 12h16M4 17h16"/></svg>
            </button>
        </nav>

        <div id="mobile-menu" class="hidden border-t border-white/10 px-5 py-4 md:hidden" data-mobile-menu>
            <div class="grid gap-2">
                <a href="{{ route('home') }}#universos" class="mobile-nav-link">Universos</a>
                <a href="{{ route('home') }}#recetas" class="mobile-nav-link">Recetas</a>
                <a href="{{ route('suggestions.create') }}" class="mobile-nav-link">Sugerir un platillo</a>
                <a href="{{ route('api.docs') }}" class="mobile-nav-link">Documentación API</a>
            </div>
        </div>
    </header>

    <main id="contenido">
        @yield('content')
    </main>

    <footer class="mt-24 border-t border-white/10 bg-black/15">
        <div class="mx-auto grid max-w-7xl gap-10 px-5 py-12 md:grid-cols-[1.4fr_1fr_1fr] lg:px-8">
            <div>
                <div class="mb-4 flex items-center gap-3 text-xl font-black"><span class="grid size-9 place-items-center rounded-xl bg-gold text-ink">M</span> MeshiVerse</div>
                <p class="max-w-md text-sm leading-6 text-cream/55">Un archivo culinario hecho para fans, cocineros curiosos y aplicaciones que necesitan compartir recetas mediante una API segura.</p>
            </div>
            <div>
                <p class="mb-4 text-xs font-extrabold uppercase tracking-[0.2em] text-gold">Explora</p>
                <div class="grid gap-3 text-sm text-cream/65"><a href="{{ route('home') }}#universos" class="hover:text-cream">Universos</a><a href="{{ route('home') }}#recetas" class="hover:text-cream">Recetas</a><a href="{{ route('suggestions.create') }}" class="hover:text-cream">Sugerir un platillo</a></div>
            </div>
            <div>
                <p class="mb-4 text-xs font-extrabold uppercase tracking-[0.2em] text-mint">Desarrolladores</p>
                <div class="grid gap-3 text-sm text-cream/65"><a href="{{ route('api.docs') }}" class="hover:text-cream">API v1</a><a href="{{ route('api.v1.status') }}" class="hover:text-cream">Estado del servicio</a></div>
            </div>
        </div>
        <div class="border-t border-white/10 px-5 py-5 text-center text-xs text-cream/35">Proyecto educativo inspirado en la cultura gastronómica del anime.</div>
    </footer>
</body>
</html>
