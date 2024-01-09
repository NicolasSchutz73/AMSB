<!-- TODO modifier le style, ajouter une popup au clic ou un messgae pour confirmer la modif -->

<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between p-4">
            <div>
                <label for="Edit Role" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                    Edit Role
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
                <form action="{{ route('roles.update', $role->id) }}" method="post">
                    @csrf
                    @method("PUT")

                    <div class="mb-4">
                        <label for="name" class="block text-md font-medium text-gray-500">Name</label>
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

                    <div class="flex justify-end">
                        <input type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" value="Update Role">
                    </div>

                </form>
            </div>
        </div>
    </div>
</x-app-layout>
