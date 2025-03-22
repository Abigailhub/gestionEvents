@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Bienvenue, {{ Auth::user()->name }} !</h1>
        <p>Vous êtes connecté en tant que {{ Auth::user()->role }}.</p>
    </div>
@endsection
