@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Panel de Company Admin</h1>
    <p>Bienvenido, {{ $user->name }}. Aquí puedes gestionar toda la organización.</p>
</div>
@endsection
