<!-- TODO : modifier le style, et pouvoir choisir plusieurs permissions dans le tableau déroulant  -->

<x-app-layout>
    <x-slot name="header">
        <div class="float-left">
            <label for="Add New Role" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">Add New Role</label>
        </div>
        <div class="float-right">
            <a href="{{ route('roles.index') }}" class="bg-blue-500 text-white py-1 px-3 rounded text-sm">&larr; Back</a>
        </div>
    </x-slot>

    <div class="flex justify-center my-6">
        <div class="w-full md:w-2/3 lg:w-1/2">
            <div class="bg-white dark:bg-gray-800 shadow rounded px-8 pt-6 pb-8 mb-4">
                <form action="{{ route('roles.store') }}" method="post">
                    @csrf

                    <div class="mb-4 flex flex-wrap">
                        <label for="name" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 block w-full md:w-1/3 px-3 mb-2 md:mb-0 md:text-right">Name</label>
                        <div class="w-full md:w-2/3">
                            <input type="text" class="form-input w-full @error('name') border-red-500 @enderror" id="name" name="name" value="{{ old('name') }}">
                            @error('name')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4 flex flex-wrap">
                        <label for="permissions" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 block w-full md:w-1/3 px-3 mb-2 md:mb-0 md:text-right">Permissions</label>
                        <div class="w-full md:w-2/3">
                            <select class="form-multiselect block w-full @error('permissions') border-red-500 @enderror" multiple id="permissions" name="permissions[]">
                                @forelse ($permissions as $permission)
                                    <option value="{{ $permission->id }}" {{ in_array($permission->id, old('permissions') ?? []) ? 'selected' : '' }}>
                                        {{ $permission->name }}
                                    </option>
                                @empty
                                    <!-- No permissions available -->
                                @endforelse
                            </select>
                            @error('permissions')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="flex justify-center mb-4">
                        <input type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded cursor-pointer" value="Add Role">
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
