@extends('index')

@section('title', 'Profil')

@section('content')
    <!-- Message d'erreur -->
    @if($errors->has('error'))
        <div class="alert">
            {{ $errors->first('error') }}
        </div>
    @endif

    @if(session()->has('ID_user'))
        <p>Bienvenue, {{ session('Nom') }} {{ session('Prenom') }}</p>

        <div class="form-container content">
            <h2>Modification de profil</h2>
            <form method="post" action="{{ route('updateProfile') }}">
                @csrf
                @method('POST')
                <div class="">
                    <label for="nom">Nom</label>
                    <input type="text" name="nom" id="nom" value="{{ session('Nom') }}">
                </div>
                <div class="">
                    <label for="prenom">Prénom</label>
                    <input type="text" name="prenom" id="prenom" value="{{ session('Prenom') }}">
                </div>
                <div class="">
                    <label for="mail">Email</label>
                    <input type="email" name="mail" id="mail" value="{{ session('Mail') }}">
                </div>
                <input type="submit" name="submit">
            </form>
        </div>

    @else
        <p>Connectez-vous pour accéder à ces informations.</p>
    @endif

@endsection
