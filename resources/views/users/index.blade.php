<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            Gérer les utilisateurs
        </h2>
    </x-slot>
    <div class="p-6">
        @can('create-user')
            <a href="{{ route('users.create') }}" class="inline-flex items-center justify-center px-4 py-2 border border-white text-white rounded-md shadow-sm hover:bg-white hover:text-black focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-white mt-3 sm:mt-0 mb-4">
                Ajouter un utilisateur
            </a>
        @endcan
         <input type="text" id="searchBar" placeholder="Rechercher des utilisateurs..." class="mt-2 mb-4 w-64 px-4 py-2 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 focus:outline-none text-gray-900">

            <div class="relative overflow-x-auto sm:rounded-lg">
                <table class="w-full text-sm text-left rtl:text-right text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                ID
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Prénom
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Nom
                            </th>
                            <th scope="col" class="px-6 py-3">
                                E-mail
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Rôle
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($users as $user)
                        <tr class="bg-white border-b hover:bg-gray-50 user-item">
                            <td class="px-6 py-4 text-gray-500">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4 text-gray-500">{{ $user->firstname }}</td>
                            <td class="px-6 py-4 text-gray-500">{{ $user->lastname }}</td>
                            <td class="px-6 py-4 text-gray-500">{{ $user->email }}</td>
                            <td class="px-6 py-4">
                                @forelse ($user->getRoleNames() as $role)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 truncate">{{ $role }}</span>
                                @empty
                                @endforelse
                            </td>
                            <td class="px-6 py-4">
                                <form action="{{ route('users.destroy', $user->id) }}" method="post">
                                    @csrf
                                    @method('DELETE')

                                    <a href="{{ route('users.show', $user->id) }}" class="font-medium text-yellow-500 hover:underline pr-4">Voir</a>

                                    @if (in_array('Super Admin', $user->getRoleNames()->toArray() ?? []) )
                                        @if (Auth::user()->hasRole('Super Admin'))
                                            <a href="{{ route('users.edit', $user->id) }}" class="font-medium text-blue-500 hover:underline pr-4">Modifier</a>
                                        @endif
                                    @else
                                        @can('edit-user')
                                            <a href="{{ route('users.edit', $user->id) }}" class="font-medium text-blue-500 hover:underline pr-4">Modifier</a>
                                        @endcan

                                        @can('delete-user')
                                            @if (Auth::user()->id!=$user->id)
                                                <button type="submit" class="font-medium text-red-500 hover:underline pr-4" onclick="return confirm('Voulez-vous supprimer cet utilisateur ?');">Supprimer</button>
                                            @endif
                                        @endcan
                                    @endif
                                </form>
                            </td>
                        </tr>
                    @empty
                        <td colspan="5" class="px-4 py-2">
                                <span class="text-red-500">
                                    <strong>Aucun utilisateur trouver !</strong>
                                </span>
                        </td>
                    @endforelse
                    </tbody>
                </table>
            </div>
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
