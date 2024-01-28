<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            Gérer les rôles
        </h2>
    </x-slot>
    <div class="p-6">
        @can('create-role')
            <a href="{{ route('roles.create') }}" class="inline-flex items-center justify-center px-4 py-2 border border-white text-white rounded-md shadow-sm hover:bg-white hover:text-black focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-white sm:ml-3 mt-3 sm:mt-0 mb-4">Add New Role</a>
        @endcan
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/4">Action</th>
                </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($roles as $role)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $role->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap flex justify-between">
                            <form action="{{ route('roles.destroy', $role->id) }}" method="post">
                                @csrf
                                @method('DELETE')

                                <a href="{{ route('roles.show', $role->id) }}" class="inline-flex items-center bg-blue-500 text-white rounded-md px-4 py-2 hover:bg-blue-600 transition duration-200 ease-in-out">Voir</a>

                                @if ($role->name != 'Super Admin')
                                    @can('edit-role')
                                        <a href="{{ route('roles.edit', $role->id) }}" class="inline-flex items-center bg-green-500 text-white rounded-md px-4 py-2 hover:bg-green-600 transition duration-200 ease-in-out">Modifier</a>
                                    @endcan

                                    @can('delete-role')
                                        @if ($role->name != Auth::user()->hasRole($role->name))
                                            <button type="submit" class="inline-flex items-center bg-red-500 text-white rounded-md px-4 py-2 hover:bg-red-600 transition duration-200 ease-in-out" onclick="return confirm('Voulez-vous supprimer ce rôle ?');">Supprimer</button>
                                        @endif
                                    @endcan
                                @endif
                            </form>
                        </td>
                    </tr>
                @empty
                    <td colspan="3" class="px-6 py-4 whitespace-nowrap">
                <span class="text-danger">
                    <strong>Aucun rôle trouver !</strong>
                </span>
                    </td>
                @endforelse
                </tbody>
            </table>
        {{ $roles->links() }}
    </div>
</x-app-layout>
