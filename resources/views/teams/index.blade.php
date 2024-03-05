<x-app-layout>
    <x-slot name="header">
        <x-crud-header title="Gérer les équipes" subtitle="Voici la liste des équipes disponible."></x-crud-header>
    </x-slot>

    <div class="p-6">
        @can('create-teams')
            <a href="{{ route('teams.create') }}" class="inline-flex items-center justify-center mb-4 px-4 py-2 border border-neutral-900 dark:border-gray-100 text-neutral-900 dark:text-gray-100 rounded-md shadow-sm hover:bg-neutral-900 dark:hover:bg-gray-100 hover:text-gray-100 dark:hover:text-neutral-900 focus:outline-none focus:ring-2 focus:ring-offset-2">
                Créer une équipe
            </a>
        @endcan
            @if (!$teams->isEmpty())
                <div class="relative overflow-x-auto sm:rounded-lg">
                    <table class="w-full text-sm text-left text-gray-400">
                        <thead class="text-xs uppercase text-gray-100 dark:text-neutral-900 bg-neutral-900 dark:bg-gray-100">
                            <tr>
                                <th scope="col" class="px-6 py-3">ID</th>
                                <th scope="col" class="px-6 py-3">Nom</th>
                                <th scope="col" class="px-6 py-3">Catégorie</th>
                                <th scope="col" class="px-6 py-3">Action</th>
                            </tr>
                        </thead>
                        @foreach ($teams as $team)
                            <tbody>
                                <tr class="bg-white border-b hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm text-neutral-600">{{ $loop->iteration }}</td>
                                    <td class="px-6 py-4 text-sm text-neutral-600">{{ $team->name }}</td>
                                    <td class="px-6 py-4 text-sm text-neutral-600">{{ $team->category }}</td>
                                    <td class="px-6 py-4 text-sm text-neutral-600">

                                        <a href="{{ route('teams.show', $team->id) }}" class="text-yellow-500 hover:underline pr-4">Voir</a>

                                        @can('edit-teams')
                                            <a href="{{ route('teams.edit', $team->id) }}" class="font-medium text-blue-500 hover:underline pr-4">Modifier</a>
                                        @endcan

                                        @can('delete-team')
                                            <form action="{{ route('teams.destroy', $team->id) }}" method="post" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" onclick="return confirm('Voulez-vous supprimer cette équipe ?');" class="font-medium text-red-500 hover:underline pr-4">Supprimer</button>
                                            </form>
                                        @endcan
                                    </td>
                                </tr>
                            </tbody>
                        @endforeach
                    </table>
                </div>
            @else
                <p class="text-danger text-center py-10">Aucune équipe disponible pour le moment.</p>
            @endif
        {{ $teams->links() }}
    </div>
</x-app-layout>
