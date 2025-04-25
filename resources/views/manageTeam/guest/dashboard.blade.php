@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Panel de Invitado</h1>
    <p>Hola {{ $user->name }}, estás accediendo como invitado.</p>
</div>
@endsection
