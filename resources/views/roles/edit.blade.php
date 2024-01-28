<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-white leading-tight">
                Modifier le rôle
            </h2>

            <a href="{{ route('roles.index') }}" class="inline-flex items-center justify-center bg-blue-500 text-white py-2 px-4 rounded text-base">&larr; Retour</a>
        </div>
    </x-slot>
    <div class="flex justify-center mt-8">
        <div class="w-8/12">
            <div class="p-6 bg-white shadow rounded">
                <form action="{{ route('roles.update', $role->id) }}" method="post">
                    @csrf
                    @method("PUT")

                    <div class="mb-4">
                        <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Nom</label>
                        <div class="mt-1">
                            <input type="text" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md @error('name') border-red-500 @enderror" id="name" name="name" value="{{ $role->name }}">
                            @if ($errors->has('name'))
                                <span class="text-red-500 text-xs">{{ $errors->first('name') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="permissions" class="block text-md font-medium text-gray-700">Permissions</label>
                        <div class="mt-1">
                            <select class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md @error('permissions') border-red-500 @enderror" multiple aria-label="Permissions" id="permissions" name="permissions[]" style="height: 210px;">
                                @forelse ($permissions as $permission)
                                    <option value="{{ $permission->id }}" {{ in_array($permission->id, $rolePermissions ?? []) ? 'selected' : '' }}>
                                        {{ $permission->name }}
                                    </option>
                                @empty

                                @endforelse
                            </select>
                            @if ($errors->has('permissions'))
                                <span class="text-red-500 text-xs">{{ $errors->first('permissions') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                            Modifier le rôle
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
