<!-- TODO : modifier le style -->
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between p-4">
            <div>
                <label for="Role Information" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                    Role Information
                </label>
            </div>
            <div>
                <a href="{{ route('roles.index') }}" class="text-sm bg-blue-500 text-white py-2 px-4 rounded">
                    &larr; Back
                </a>
            </div>
        </div>
    </x-slot>
    <div class="flex justify-center mt-8">
        <div class="w-8/12">
            <div class="p-6">
                <div class="px-6 py-4">
                    <div class="flex mb-4">
                        <label for="name" class="text-gray-500 dark:text-gray-400 w-1/3 text-right pr-4 align-middle">
                            <strong>Name:</strong>
                        </label>
                        <div class="w-2/3 text-gray-500 dark:text-gray-400">
                            {{ $role->name }}
                        </div>
                    </div>

                    <div class="flex mb-4">
                        <label for="roles" class="text-gray-500 dark:text-gray-400  w-1/3 text-right pr-4 align-middle">
                            <strong>Permissions:</strong>
                        </label>
                        <div class="w-2/3">
                            @if ($role->name=='Super Admin')
                                <span class="bg-blue-500 text-white py-1 px-3 rounded">All</span>
                            @else
                                @forelse ($rolePermissions as $permission)
                                    <span class="bg-blue-500 text-white py-1 px-3 rounded">{{ $permission->name }}</span>
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
