@extends('index')

@section('title', 'Register')

@section('content')


    <!-- Message d'erreur -->
    @if($errors->has('error'))
        <div class="alert">
            {{ $errors->first('error') }}
        </div>
    @endif

    <div class="login">
        <div class="oval"></div>
        <img class="imgBall" src="{{asset('img/ballon.png')}}">
        <div class="oval"></div>

        <div class="content">
            <h2>Connexion</h2>
            <form method="post" action="{{ route('login') }}">
                @csrf
                @method('POST')
                <!-- Email input -->
                <div class="">
                    <label class="" for="email">Adresse email</label>
                    <input name="email" type="email" id="email" class="" />
                </div>

                <!-- Password input -->
                <div class="">
                    <label class="" for="mdp">Mot de passe </label>
                    <input name="password" type="password" id="mdp" class="" />
                </div>

                <!-- Submit button -->
                <input type="submit" name="submit" class="">
            </form>
        </div>
        <div class="panier">
            <script type="module" src="https://unpkg.com/@splinetool/viewer@1.0.18/build/spline-viewer.js"></script>
            <spline-viewer url="https://prod.spline.design/EQ8npRivENyeIbp7/scene.splinecode"></spline-viewer>
        </div>
    </div>

@endsection
