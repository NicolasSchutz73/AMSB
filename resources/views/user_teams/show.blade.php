@if ($teams->isEmpty())
    <h1>Mon équipe</h1>
    <p>Vous n'êtes pas encore associé à une équipe.</p>
@else
    @if ($teams->count() > 1)
        <h1>Mes équipes</h1>
    @else
        <h1>Mon équipe</h1>
    @endif

    @foreach ($teams as $team)
        <div>
            <span>Nom : {{ $team->name }}</span>
            <span>Catégorie : {{ $team->category }}</span>
        </div>

        <h2>Membres de l'équipe</h2>
        @forelse ($team->users as $user)
            <div>
                <span>Prénom : {{ $user->firstname }}</span>
                <span>Nom : {{ $user->lastname }}</span>
                <span>Rôle :
                    @forelse ($user->getRoleNames() as $role)
                        <span>{{ $role }}</span>
                    @empty
                        Aucun rôle assigné
                    @endforelse
                </span>
                <!-- Autres détails du membre de l'équipe si nécessaire -->
            </div>
        @empty
            <p>Aucun membre dans cette équipe pour le moment.</p>
        @endforelse

        <h2>Entraineurs de l'équipe</h2>
        @forelse ($team->coach()->get() as $coach)
            <div>
                <span>Prénom de l'entraîneur : {{ $coach->firstname }}</span>
                <span>Nom de l'entraîneur : {{ $coach->lastname }}</span>
                <!-- Autres détails de l'entraîneur de l'équipe si nécessaire -->
            </div>
        @empty
            <p>Aucun entraîneur dans cette équipe pour le moment.</p>
        @endforelse
    @endforeach
@endif
