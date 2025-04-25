@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Panel de Project Manager</h1>
    <p>Bienvenido, {{ $user->name }}. Aquí puedes gestionar los proyectos asignados.</p>
</div>
@endsection
