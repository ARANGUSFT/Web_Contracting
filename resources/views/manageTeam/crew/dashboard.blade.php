@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Panel de Crew</h1>
    <p>Bienvenido, {{ $user->name }}. Aquí puedes ver tus asignaciones.</p>
</div>
@endsection
