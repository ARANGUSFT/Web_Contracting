@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Panel de Manager</h1>
    <p>Hola {{ $user->name }}, desde aquí puedes supervisar a tu equipo.</p>
</div>
@endsection
