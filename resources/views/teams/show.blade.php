<h1>Détails de l'équipe</h1>
<div>
    <span>ID : {{ $team->id }}</span>
    <span>Nom : {{ $team->name }}</span>
    <span>Catégorie : {{ $team->category }}</span>

    <h2>Membres de l'équipe</h2>
    @forelse ($users as $user)
        @if (!$user->hasRole('coach'))  {{-- Exclure les utilisateurs avec le rôle de coach --}}
            <div>
                <span>Prénom d'utilisateur : {{ $user->firstname }}</span>
                <span>Nom d'utilisateur : {{ $user->lastname }}</span>
                <span>Rôle :
                    @forelse ($user->getRoleNames() as $role)
                        <span>{{ $role }}</span>
                    @empty
                        Aucun rôle assigné
                    @endforelse
                </span>
            </div>
        @endif
    @empty
        <p>Aucun membre dans cette équipe pour le moment.</p>
    @endforelse

    <h2>Entraineurs de l'équipe</h2>
    @forelse ($coaches as $coach)
        <div>
            <span>Prénom de l'entraineur : {{ $coach->firstname }}</span>
            <span>Nom de l'entraineur : {{ $coach->lastname }}</span>
        </div>
    @empty
        <p>Aucun entraineur dans cette équipe pour le moment.</p>
    @endforelse
</div>
