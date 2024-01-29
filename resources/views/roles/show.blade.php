<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-white leading-tight">
                Information du rôle
            </h2>
            <a href="{{ route('roles.index') }}" class="inline-flex items-center justify-center bg-blue-500 text-white py-2 px-4 rounded text-base">&larr; Retour</a>
        </div>
    </x-slot>
    <div class="p-6">
        <div class="px-6 py-2">
            <div>
                <label for="name" class="text-gray-400 text-left">
                    <strong>Nom du rôle :</strong>
                </label>
                <div class="text-gray-400">
                    {{ $role->name }}
                </div>
            </div>
            <div>
                <label for="roles" class="text-gray-400 text-left">
                    <strong>Permissions attribuées:</strong>
                </label>
                <div>
                    @if ($role->name=='Super Admin')
                        <span class="text-gray-400">Toutes les permissions</span>
                    @else
                        @forelse ($rolePermissions as $permission)
                            <span class="text-gray-400">{{ $permission->name }} | </span>
                        @empty
                        @endforelse
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
