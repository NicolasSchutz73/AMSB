<h1>Liste des équipes</h1>

@can('create-teams')
    <a href="{{ route('teams.create') }}">
        Ajouter une équipe
    </a>
@endcan

@forelse ($teams as $team)
    <div>
        <span>ID : {{ $loop->iteration }}</span>
        <span>Nom : {{ $team->name }}</span>
        <span>Catégorie : {{ $team->category }}</span>
        <a href="{{ route('teams.show', $team->id) }}">Voir</a>

        @can('edit-teams')
            <a href="{{ route('teams.edit', $team->id) }}">Modifier</a>
        @endcan

        @can('delete-team')
            <form action="{{ route('teams.destroy', $team->id) }}" method="post" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" onclick="return confirm('Voulez-vous supprimer cette équipe ?');">Supprimer</button>
            </form>
        @endcan
    </div>
@empty
    <p>Aucune équipe disponible pour le moment.</p>
@endforelse

{{ $teams->links() }}  {{-- Affiche la pagination si nécessaire --}}
