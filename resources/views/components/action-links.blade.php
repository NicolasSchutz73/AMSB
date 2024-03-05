<div>
    <a href="{{ route($showRoute, $id) }}" class="text-yellow-500 hover:underline pr-4">Voir</a>
    @can('edit-teams')
        <a href="{{ route($editRoute, $id) }}" class="font-medium text-blue-500 hover:underline pr-4">Modifier</a>
    @endcan
    @can('delete-team')
        <form action="{{ route($deleteRoute, $id) }}" method="post" style="display: inline;">
            @csrf
            @method('DELETE')
            <button type="submit" onclick="return confirm('Voulez-vous supprimer cette équipe ?');" class="font-medium text-red-500 hover:underline pr-4">Supprimer</button>
        </form>
    @endcan
</div>
