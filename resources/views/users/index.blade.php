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
        <table class="w-full table-auto bg-white rounded">
            <thead>
            <tr>
                <th class="px-4 py-2">S#</th>
                <th class="px-4 py-2">Firstname</th>
                <th class="px-4 py-2">Lastname</th>
                <th class="px-4 py-2">Email</th>
                <th class="px-4 py-2">Roles</th>
                <th class="px-4 py-2">Action</th>
            </tr>
            </thead>
            <tbody>
            @forelse ($users as $user)
                <tr>
                    <th class="px-4 py-2">{{ $loop->iteration }}</th>
                    <td class="px-4 py-2">{{ $user->firstname }}</td>
                    <td class="px-4 py-2">{{ $user->lastname }}</td>
                    <td class="px-4 py-2">{{ $user->email }}</td>
                    <td class="px-4 py-2">
                        @forelse ($user->getRoleNames() as $role)
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">{{ $role }}</span>
                        @empty
                        @endforelse
                    </td>
                    <td class="px-4 py-2">
                        <form action="{{ route('users.destroy', $user->id) }}" method="post">
                            @csrf
                            @method('DELETE')

                            <a href="{{ route('users.show', $user->id) }}" class="inline-flex items-center justify-center px-4 py-2 bg-yellow-500 text-white rounded-md shadow-sm hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 sm:ml-3 mt-3 sm:mt-0"><i class="fas fa-eye"></i> Show</a>

                            @if (in_array('Super Admin', $user->getRoleNames()->toArray() ?? []) )
                                @if (Auth::user()->hasRole('Super Admin'))
                                    <a href="{{ route('users.edit', $user->id) }}" class="inline-flex items-center justify-center px-4 py-2 bg-blue-500 text-white rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 mt-3 sm:mt-0"><i class="fas fa-pencil-alt"></i> Edit</a>
                                @endif
                            @else
                                @can('edit-user')
                                    <a href="{{ route('users.edit', $user->id) }}" class="inline-flex items-center justify-center px-4 py-2 bg-blue-500 text-white rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 mt-3 sm:mt-0"><i class="fas fa-pencil-alt"></i> Edit</a>
                                @endcan

                                @can('delete-user')
                                    @if (Auth::user()->id!=$user->id)
                                        <button type="submit" class="inline-flex items-center justify-center px-4 py-2 bg-red-500 text-white rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 mt-3 sm:mt-0" onclick="return confirm('Do you want to delete this user?');"><i class="fas fa-trash"></i> Delete</button>
                                    @endif
                                @endcan
                            @endif

                        </form>
                    </td>
                </tr>
            @empty
                <td colspan="5" class="px-4 py-2">
                      <span class="text-red-500">
                          <strong>No User Found!</strong>
                      </span>
                </td>
            @endforelse
            </tbody>
        </table>

        {{ $users->links() }}
    </div>
</x-app-layout>
