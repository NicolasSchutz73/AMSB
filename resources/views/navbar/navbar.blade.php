<nav class="navbar">
    <div class="container">
        <a class="brand" href="{{ route('app_home') }}">AMSB</a>
        <div class="menu-toggle" id="mobile-menu">
            <div></div>
            <div></div>
            <div></div>
        </div>
        <div class="navbar-links" id="navbar-links">
            @if(session()->has('ID_user'))
                <a href="{{ route('app_home') }}">Home</a>
                <a href="{{ route('profil') }}">Profil</a>
                <a href="{{ route('deconnexion') }}">Deconnexion</a>
            @else
                <a href="{{ route('app_home') }}">Home</a>
                <a href="{{ route('register') }}">Inscription</a>
                <a href="{{ route('login') }}">Connexion</a>
            @endif
        </div>
    </div>
</nav>

