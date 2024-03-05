<x-app-layout>
    <x-slot name="header">
        <x-crud-header title="Gérer les utilisateurs" subtitle="Voici la liste des utilisateurs disponible."></x-crud-header>
    </x-slot>

    <div class="p-6">
        @can('create-user')
            <x-create-button route="users.create" label="Ajouter un utilisateur" />
        @endcan

        <input type="text" id="searchBar" placeholder="Rechercher des utilisateurs..." class="mt-2 mb-4 w-64 px-4 py-2 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 focus:outline-none text-gray-900">

        @if (!$users->isEmpty())
            <x-dynamic-table :headers="['ID', 'Prénom', 'Nom', 'E-mail', 'Rôle', 'Action']">
                @foreach ($users as $user)
                    <tr class="bg-white border-b hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm text-neutral-600">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4 text-sm text-neutral-600">{{ $user->firstname }}</td>
                        <td class="px-6 py-4 text-sm text-neutral-600">{{ $user->lastname }}</td>
                        <td class="px-6 py-4 text-sm text-neutral-600">{{ $user->email }}</td>
                        <td class="px-6 py-4">
                            @forelse ($user->getRoleNames() as $role)
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 truncate">{{ $role }}</span>
                            @empty
                            @endforelse
                        </td>
                        <td class="px-6 py-4 text-sm text-neutral-600">
                            <!-- Utilisation du composant Action Links -->
                            <x-action-links :showRoute="'users.show'" :editRoute="'users.edit'" :deleteRoute="'users.destroy'" :id="$user->id"/>
                        </td>
                    </tr>
                @endforeach
            </x-dynamic-table>
        @else
            <p class="text-danger text-center py-10">Aucun utilisateur disponible pour le moment.</p>
        @endif
        {{ $users->links() }}
    </div>
</x-app-layout>

<script>
    document.getElementById('searchBar').addEventListener('keyup', function() {
        var searchValue = this.value.toLowerCase();
        var userItems = document.querySelectorAll('.user-item');

        userItems.forEach(function(item) {
            if (item.textContent.toLowerCase().includes(searchValue)) {
                item.style.display = ''; // L'élément correspond, on l'affiche
            } else {
                item.style.display = 'none'; // L'élément ne correspond pas, on le cache
            }
        });
    });
</script>
