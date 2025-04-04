
@extends('layouts.app')

@section('content')

<div class="container py-5">

    <h2 class="mb-4 fw-bold text-primary">Edit Profile</h2>

    {{-- Mensaje de actualización --}}
    @if (session('status') === 'profile-updated')
        <div class="alert alert-success">Profile updated successfully.</div>
    @endif

    <div class="row g-4">

        {{-- Sección 1: Información del perfil --}}
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white fw-bold text-uppercase">
                    🧍 Update Profile Information
                </div>
                <div class="card-body">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>
        </div>

        {{-- Sección 2: Cambiar contraseña --}}
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-white fw-bold text-uppercase">
                    🔒 Change Password
                </div>
                <div class="card-body">
                    @include('profile.partials.update-password-form')
                </div>
            </div>
        </div>

        {{-- Sección 3: Eliminar cuenta --}}
        <div class="col-md-6">
            <div class="card shadow-sm border border-danger">
                <div class="card-header bg-white text-danger fw-bold text-uppercase">
                    ❌ Delete Account
                </div>
                <div class="card-body">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>

    </div>
</div>

@endsection
