@extends('index')

@section('title', 'Inscription')

@section('content')
    <!-- Message d'erreur -->
    @if($errors->has('error'))
        <div class="alert">
            {{ $errors->first('error') }}
        </div>
    @endif

    <div class="login">
        <div class="oval"></div>
        <img style="display: none" class="imgBall" src="{{asset('img/ballon.png')}}">
        <div class="oval"></div>

        <div class="form-container content">
            <h2>Inscription</h2>
            <form id="registration-form" method="post" action="{{ route('register') }}">
                @csrf
                @method('POST')
                <!-- Étape 1 -->
                <div class="form-step active" data-step="1">
                    <div class="">
                        <label for="lastname">Nom</label>
                        <input type="text" name="lastname" required><br>
                    </div>
                    <div class="">
                        <label for="firstname">Prénom</label>
                        <input type="text" name="firstname" required><br>
                    </div>
                    <button type="button" class="next">Suivant</button>
                </div>

                <!-- Étape 2 -->
                <div class="form-step" data-step="2">
                    <div class="">
                        <label for="email">Email</label>
                        <input type="email" name="email" required><br>

                    </div>
                    <div class="">
                        <label for="emailConf">Confirmation email</label>
                        <input type="email" name="emailConf" required><br>
                    </div>
                    <button type="button" class="prev">Précédent</button>
                    <button type="button" class="next">Suivant</button>
                </div>

                <!-- Étape 3 -->
                <div class="form-step" data-step="3">
                    <div class="">
                        <label for="password">Mot de passe</label>
                        <input type="password" name="password" required><br>
                    </div>
                    <div class="">
                        <label for="passwordConf">Confirmation mot de passe</label>
                        <input type="password" name="passwordConf" required><br>

                    </div>
                    <button type="button" class="prev">Précédent</button>
                    <input type="submit" name="submit">
                </div>
            </form>
        </div>

    </div>
    <div class="personnageBloc">
        <img src="{{asset('img/perso1.png')}}" class="active">
        <img src="{{asset('img/perso2.png')}}">
        <img src="{{asset('img/perso4.png')}}">
    </div>
    <div class="ovalBas"></div>
@endsection
