@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">👤 Editar Perfil</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- ✅ Formulario de Edición de Perfil --}}
    <form method="POST" action="{{ route(Route::currentRouteName(), $user->id) }}" class="mb-5">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name" class="form-label">Nombre</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control" required>
            @error('name') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Correo Electrónico</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control" required>
            @error('email') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <button type="submit" class="btn btn-primary">💾 Guardar Cambios</button>
    </form>

    {{-- ✅ Formulario de Cambio de Contraseña --}}
    <hr>
    <h4 class="mb-3">🔒 Cambiar Contraseña</h4>

    <form method="POST" action="{{ route(str_replace('.edit', '.password.update', Route::currentRouteName())) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="current_password" class="form-label">Contraseña Actual</label>
            <input type="password" name="current_password" class="form-control" required>
            @error('current_password') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Nueva Contraseña</label>
            <input type="password" name="password" class="form-control" required>
            @error('password') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirmar Contraseña</label>
            <input type="password" name="password_confirmation" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-warning">🔁 Cambiar Contraseña</button>
    </form>
</div>
@endsection
