@extends('layouts.dashboard')

@section('title', 'Mi perfil — MeshiVerse')

@section('content')
    <div class="max-w-5xl"><p class="section-kicker">Identidad del aventurero</p><h1 class="section-title">Mi perfil</h1><p class="mt-3 text-sm text-cream/50">Actualiza tus datos personales y la seguridad de tu cuenta.</p></div>

    <div class="mt-8 grid max-w-5xl gap-7 xl:grid-cols-2">
        <form method="POST" action="{{ route('profile.update') }}" class="dashboard-card">
            @csrf
            @method('PATCH')
            <p class="section-kicker mb-1">Datos básicos</p><h2 class="text-2xl font-black">Información personal</h2>
            <div class="mt-6 space-y-5">
                <div><label for="name" class="form-label">Nombre</label><input id="name" name="name" value="{{ old('name', $user->name) }}" class="form-control" required>@error('name')<p class="form-error">{{ $message }}</p>@enderror</div>
                <div><label for="email" class="form-label">Correo electrónico</label><input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control" required>@error('email')<p class="form-error">{{ $message }}</p>@enderror</div>
                <div class="rounded-xl border border-gold/10 bg-ink/45 p-4 text-xs text-cream/45"><strong class="text-gold">Rol:</strong> {{ $user->isAdmin() ? 'Administrador' : 'Usuario' }} · asignado por el sistema.</div>
                <button class="primary-button w-full">Guardar perfil</button>
            </div>
        </form>

        <form method="POST" action="{{ route('profile.password.update') }}" class="dashboard-card">
            @csrf
            @method('PATCH')
            <p class="section-kicker mb-1">Seguridad</p><h2 class="text-2xl font-black">Cambiar contraseña</h2>
            <div class="mt-6 space-y-5">
                <div><label for="current_password" class="form-label">Contraseña actual</label><input id="current_password" type="password" name="current_password" class="form-control" required>@error('current_password')<p class="form-error">{{ $message }}</p>@enderror</div>
                <div><label for="password" class="form-label">Nueva contraseña</label><input id="password" type="password" name="password" class="form-control" required>@error('password')<p class="form-error">{{ $message }}</p>@enderror</div>
                <div><label for="password_confirmation" class="form-label">Confirmar contraseña</label><input id="password_confirmation" type="password" name="password_confirmation" class="form-control" required></div>
                <button class="secondary-button w-full">Actualizar contraseña</button>
            </div>
        </form>
    </div>
@endsection
