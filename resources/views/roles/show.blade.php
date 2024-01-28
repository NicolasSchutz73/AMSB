<!-- TODO : modifier le style -->
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-white leading-tight">
                Information du rôle
            </h2>

            <a href="{{ route('roles.index') }}" class="inline-flex items-center justify-center bg-blue-500 text-white py-2 px-4 rounded text-base">&larr; Retour</a>
        </div>
    </x-slot>
    <div class="flex justify-center mt-8">
        <div class="w-8/12">
            <div class="p-6">
                <div class="px-6 py-4">
                    <div class="flex mb-4">
                        <label for="name" class="text-gray-500 dark:text-gray-400 w-1/3 text-right pr-4 align-middle">
                            <strong>Nom du rôle :</strong>
                        </label>
                        <div class="w-2/3 text-gray-500 dark:text-gray-400">
                            {{ $role->name }}
                        </div>
                    </div>
                    <div class="flex mb-4">
                        <label for="roles" class="text-gray-500 dark:text-gray-400 w-1/3 text-right pr-4 align-middle">
                            <strong>Permissions attribuées:</strong>
                        </label>
                        <div class="w-2/3">
                            @if ($role->name=='Super Admin')
                                <span class="text-white py-1 px-3 rounded border border-white">All</span>
                            @else
                                @forelse ($rolePermissions as $permission)
                                    <span class="text-white py-1 px-3 rounded border border-white">{{ $permission->name }}</span>
                                @empty
                                @endforelse
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
