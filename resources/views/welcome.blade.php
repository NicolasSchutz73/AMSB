<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- PWA  -->
    <meta name="theme-color" content="#6777ef"/>
    <link rel="apple-touch-icon" href="{{ asset('logo.PNG') }}">
    <link rel="manifest" href="{{ asset('/manifest.json') }}">
</head>
<body class="dark:bg-neutral-900">
<div class="flex items-center justify-center min-h-screen">
    @if (Route::has('login'))
        <div class="flex flex-col items-center space-y-4">
            <div class="flex flex-col items-center justify-center mb-4">
                @include('components.application-logo-big')
                <p class="text-sm text-neutral-600 dark:text-gray-300 mt-2">Bienvenue sur notre application !</p>
            </div>
        @auth
                <a href="{{ url('/dashboard') }}" class="inline-block text-slate-200">
                    <x-bouton-stylise content="Dashboard" />
                </a>
            @else
                <a href="{{ route('login') }}" class="inline-block text-neutral-900 dark:text-gray-100">
                    Connexion
                </a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="inline-block">
                        <x-bouton-stylise content="Inscription" />
                    </a>
                @endif
            @endauth
        </div>
    @endif
</div>

<script src="{{ asset('/sw.js') }}"></script>
<script>
    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.register('/sw.js').then(function(registration) {
            console.log('Service worker registration succeeded:', registration);
        }, function(error) {
            console.log('Service worker registration failed:', error);
        });
    } else {
        console.log('Service workers are not supported.');
    }
</script>
</body>
</html>
