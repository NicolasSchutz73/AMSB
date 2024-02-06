<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            Gérer les utilisateurs
        </h2>
    </x-slot>

    <div class="p-6">
        @can('create-user')
            <a href="{{ route('users.create') }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700"> Ajouter un utilisateur </a>
        @endcan

        <br>
        <input type="text" id="searchBar" placeholder="Rechercher des utilisateurs..." class="mt-2 mb-4">

        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prénom</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">E-mail</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rôle</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
            </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
            @forelse ($users as $user)
                <tr class="hover:bg-gray-50 user-item">
                    <td class="px-6 py-4 whitespace-nowrap">{{ $loop->iteration }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $user->firstname }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $user->lastname }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $user->email }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @foreach ($user->getRoleNames() as $role)
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">{{ $role }}</span>
                        @endforeach
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <!-- Actions -->
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                        Aucun utilisateur trouvé.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>

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
