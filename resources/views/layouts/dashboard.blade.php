<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#4b1018">
    <title>@yield('title', 'Panel — MeshiVerse')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="min-h-screen bg-ink font-sans text-cream antialiased">
    <div class="pointer-events-none fixed inset-0 -z-10 dungeon-pattern opacity-40"></div>
    <div class="min-h-screen lg:grid lg:grid-cols-[280px_1fr]">
        <aside class="border-b border-gold/15 bg-night/95 px-5 py-5 lg:sticky lg:top-0 lg:h-screen lg:border-b-0 lg:border-r lg:px-6 lg:py-7">
            <div class="flex items-center justify-between lg:block">
                <a href="{{ route('home') }}" class="flex items-center gap-3">
                    <span class="ornate-border grid size-11 place-items-center rounded-xl bg-wine font-display text-xl font-black">M</span>
                    <span><strong class="block font-display text-xl">Meshi<span class="text-gold">Verse</span></strong><span class="text-[9px] uppercase tracking-[.22em] text-cream/35">Mesa de aventureros</span></span>
                </a>
                <a href="{{ route('home') }}" class="rounded-xl border border-gold/20 px-3 py-2 text-xs font-black text-gold lg:hidden">Ver sitio</a>
            </div>

            <div class="mt-7 rounded-2xl border border-gold/15 bg-ink/45 p-4">
                <div class="flex items-center gap-3">
                    <span class="grid size-10 place-items-center rounded-full bg-gradient-to-br from-gold to-amber font-black text-ink">{{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}</span>
                    <div class="min-w-0"><p class="truncate text-sm font-black">{{ auth()->user()->name }}</p><p class="text-[10px] uppercase tracking-widest text-mint">{{ auth()->user()->isAdmin() ? 'Administrador' : 'Usuario' }}</p></div>
                </div>
            </div>

            <nav class="mt-6 grid gap-1" aria-label="Panel de usuario">
                @if (auth()->user()->isAdmin())
                    <p class="mb-2 px-4 text-[10px] font-black uppercase tracking-[.2em] text-cream/30">Administración</p>
                    <a href="{{ route('admin.dashboard') }}" class="dashboard-link {{ request()->routeIs('admin.dashboard') ? 'dashboard-link-active' : '' }}"><span>⌂</span> Resumen</a>
                    <a href="{{ route('admin.recipes.create') }}" class="dashboard-link {{ request()->routeIs('admin.recipes.*') ? 'dashboard-link-active' : '' }}"><span>✦</span> Crear receta</a>
                    <a href="{{ route('admin.dish.index') }}" class="dashboard-link {{ request()->routeIs('admin.dish.*') ? 'dashboard-link-active' : '' }}"><span>☀</span> Receta del día</a>
                    <p class="mb-2 mt-6 px-4 text-[10px] font-black uppercase tracking-[.2em] text-cream/30">Cuenta</p>
                    <a href="{{ route('profile.edit') }}" class="dashboard-link {{ request()->routeIs('profile.*') ? 'dashboard-link-active' : '' }}"><span>♙</span> Perfil</a>
                    <a href="{{ route('admin.api.docs') }}" class="dashboard-link {{ request()->routeIs('admin.api.docs', 'admin.api.tokens.*', 'admin.api.connections.*') ? 'dashboard-link-active' : '' }}"><span>⚙</span> Configuración API</a>
                    <a href="{{ route('admin.api.hamas.index') }}" class="dashboard-link {{ request()->routeIs('admin.api.hamas.*') ? 'dashboard-link-active' : '' }}"><span>◇</span> Datos de Hama</a>
                @else
                    <p class="mb-2 px-4 text-[10px] font-black uppercase tracking-[.2em] text-cream/30">Mi recetario</p>
                    <a href="{{ route('user.dashboard') }}" class="dashboard-link {{ request()->routeIs('user.dashboard') ? 'dashboard-link-active' : '' }}"><span>⌂</span> Resumen</a>
                    <a href="{{ route('favorites.index') }}" class="dashboard-link {{ request()->routeIs('favorites.*') ? 'dashboard-link-active' : '' }}"><span>★</span> Favoritos</a>
                    <a href="{{ route('suggestions.create') }}" class="dashboard-link {{ request()->routeIs('suggestions.*') ? 'dashboard-link-active' : '' }}"><span>✎</span> Sugerir receta</a>
                    <p class="mb-2 mt-6 px-4 text-[10px] font-black uppercase tracking-[.2em] text-cream/30">Cuenta</p>
                    <a href="{{ route('profile.edit') }}" class="dashboard-link {{ request()->routeIs('profile.*') ? 'dashboard-link-active' : '' }}"><span>♙</span> Perfil</a>
                @endif
            </nav>

            <div class="mt-6 border-t border-gold/10 pt-5 lg:absolute lg:bottom-7 lg:left-6 lg:right-6">
                <a href="{{ route('home') }}" class="dashboard-link"><span>←</span> Volver al recetario</a>
                <form method="POST" action="{{ route('logout') }}">@csrf<button class="dashboard-link w-full text-left"><span>⇥</span> Cerrar sesión</button></form>
            </div>
        </aside>

        <main class="min-w-0 px-5 py-8 sm:px-8 lg:px-10 lg:py-10 xl:px-14">
            @if (session('success'))
                <div class="mb-6 rounded-xl border border-mint/35 bg-mint/10 px-5 py-4 text-sm font-bold text-mint" role="status">{{ session('success') }}</div>
            @endif
            @yield('content')
        </main>
    </div>
    @stack('scripts')
</body>
</html>
