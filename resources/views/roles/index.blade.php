<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            Gérer les rôles
        </h2>
    </x-slot>
    <div class="p-6">
        @can('create-role')
            <a href="{{ route('roles.create') }}" class="inline-flex items-center justify-center px-4 py-2 border border-white text-white rounded-md shadow-sm hover:bg-white hover:text-black focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-white mt-3 sm:mt-0 mb-4">
                Ajouter un rôle
            </a>
        @endcan
            <div class="relative overflow-x-auto sm:rounded-lg">

                <table class="w-full text-sm text-left rtl:text-right text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                ID
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Nom
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse ($roles as $role)
                        <tr class="bg-white border-b hover:bg-gray-50">
                            <td class="px-6 py-4 text-gray-500">
                                {{ $loop->iteration }}
                            </td>
                            <td class="px-6 py-4 text-gray-500">
                                {{ $role->name }}
                            </td>
                            <td class="px-6 py-4">
                                <form action="{{ route('roles.destroy', $role->id) }}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <a href="{{ route('roles.show', $role->id) }}" class="font-medium text-yellow-500 hover:underline pr-4">Voir</a>
                                    @if ($role->name != 'Super Admin')
                                        @can('edit-role')
                                            <a href="{{ route('roles.edit', $role->id) }}" class="font-medium text-blue-500 hover:underline pr-4">Modifier</a>
                                        @endcan

                                        @can('delete-role')
                                            @if ($role->name != Auth::user()->hasRole($role->name))
                                                <button type="submit" class="font-medium text-red-500 hover:underline pr-4" onclick="return confirm('Voulez-vous supprimer ce rôle ?');">Supprimer</button>
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
            </div>
        {{ $roles->links() }}
    </div>
</x-app-layout>
