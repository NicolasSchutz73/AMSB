<!-- TODO : Modifier la mise en page -->

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            Gérer les utilisateurs
        </h2>
    </x-slot>

    <div class="p-6">
        @can('create-user')
            <a href="{{ route('users.create') }}" class="inline-flex items-center justify-center px-4 py-2 border border-white text-white rounded-md shadow-sm hover:bg-white hover:text-black focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-white sm:ml-3 mt-3 sm:mt-0 mb-4">
                Ajouter un utilisateur
            </a>
        @endcan
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prénom</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">E-mail</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rôle</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/4">Action</th>
            </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
            @forelse ($users as $user)
                <tr class="hover:bg-gray-50">
                    <th class="px-6 py-4 whitespace-nowrap">{{ $loop->iteration }}</th>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $user->firstname }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $user->lastname }}</td>
                    <td class="ppx-6 py-4 whitespace-nowrap">{{ $user->email }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @forelse ($user->getRoleNames() as $role)
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">{{ $role }}</span>
                        @empty
                        @endforelse
                    </td>
                    <td class="px-4 py-2">
                        <form action="{{ route('users.destroy', $user->id) }}" method="post">
                            @csrf
                            @method('DELETE')

                            <a href="{{ route('users.show', $user->id) }}" class="inline-flex items-center justify-center px-4 py-2 bg-yellow-500 text-white rounded-md shadow-sm hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 sm:ml-3 mt-3 sm:mt-0">Voir</a>

                            @if (in_array('Super Admin', $user->getRoleNames()->toArray() ?? []) )
                                @if (Auth::user()->hasRole('Super Admin'))
                                    <a href="{{ route('users.edit', $user->id) }}" class="inline-flex items-center justify-center px-4 py-2 bg-blue-500 text-white rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 mt-3 sm:mt-0">Modifier</a>
                                @endif
                            @else
                                @can('edit-user')
                                    <a href="{{ route('users.edit', $user->id) }}" class="inline-flex items-center justify-center px-4 py-2 bg-blue-500 text-white rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 mt-3 sm:mt-0">Modifier</a>
                                @endcan

                                @can('delete-user')
                                    @if (Auth::user()->id!=$user->id)
                                        <button type="submit" class="inline-flex items-center justify-center px-4 py-2 bg-red-500 text-white rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 mt-3 sm:mt-0" onclick="return confirm('Voulez-vous supprimer cet utilisateur ?');">Supprimer</button>
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

        {{ $users->links() }}
    </div>
</x-app-layout>
