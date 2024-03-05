<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            Gérer les utilisateurs
        </h2>
    </x-slot>
    <div class="p-6">
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
                </tr>
                </thead>
                <tbody>
                @forelse($users as $user)
                    <tr class="bg-white border-b hover:bg-gray-50 user-item cursor-pointer" onclick="window.location='{{ route('usersMess.show', $user->id) }}'">
                        <td class="px-6 py-4 text-gray-500">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4 text-gray-500">{{ $user->firstname }}</td>
                        <td class="px-6 py-4 text-gray-500">{{ $user->lastname }}</td>
                        <td class="px-6 py-4 text-gray-500">{{ $user->email }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">Aucun utilisateur trouvé.</td>
                    </tr>
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
