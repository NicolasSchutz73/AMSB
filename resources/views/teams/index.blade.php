<x-app-layout>
    <x-slot name="header">
        <x-crud-header title="Gérer les équipes" subtitle="Voici la liste des équipes disponible."></x-crud-header>
    </x-slot>

    <div class="p-6">
        @can('create-teams')
            <x-create-button route="teams.create" label="Ajouter une équipe" />
        @endcan
            @if (!$teams->isEmpty())
                <x-dynamic-table :headers="['ID', 'Nom', 'Catégorie', 'Action']">
                    @foreach ($teams as $team)
                        <tr class="bg-white border-b hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm text-neutral-600">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4 text-sm text-neutral-600">{{ $team->name }}</td>
                            <td class="px-6 py-4 text-sm text-neutral-600">{{ $team->category }}</td>
                            <td class="px-6 py-4 text-sm text-neutral-600">
                                <x-action-links :showRoute="'teams.show'" :editRoute="'teams.edit'" :deleteRoute="'teams.destroy'" :id="$team->id"/>
                            </td>
                        </tr>
                    @endforeach
                </x-dynamic-table>
            @else
                <p class="text-danger text-center py-10">Aucune équipe disponible pour le moment.</p>
            @endif
        {{ $teams->links() }}
    </div>
</x-app-layout>
