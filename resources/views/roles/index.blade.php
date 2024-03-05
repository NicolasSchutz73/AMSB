<x-app-layout>
    <x-slot name="header">
        <!-- Utilisation du composant CRUD Header -->
        <x-crud-header title="Gérer les rôles" subtitle="Voici la liste des rôles disponible."></x-crud-header>
    </x-slot>

    <div class="p-6">
        @can('create-role')
            <!-- Utilisation du composant Create Button -->
            <x-create-button route="roles.create" label="Ajouter un rôle" />
        @endcan

        @if (!$roles->isEmpty())
            <x-dynamic-table :headers="['ID', 'Nom', 'Action']">
                @foreach ($roles as $role)
                    <tr class="bg-white border-b hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm text-neutral-600">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4 text-sm text-neutral-600">{{ $role->name }}</td>
                        <td class="px-6 py-4 text-sm text-neutral-600">
                            <!-- Utilisation du composant Action Links -->
                            <x-action-links :showRoute="'roles.show'" :editRoute="'roles.edit'" :deleteRoute="'roles.destroy'" :id="$role->id"/>
                        </td>
                    </tr>
                @endforeach
            </x-dynamic-table>
        @else
            <p class="text-danger text-center py-10">Aucun rôle disponible pour le moment.</p>
        @endif

        {{ $roles->links() }}
    </div>
</x-app-layout>
