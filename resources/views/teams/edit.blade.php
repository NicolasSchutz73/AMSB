<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="mb-1 font-semibold text-xl text-neutral-900 dark:text-gray-100">Édition de l'équipe</h2>

            <a href="{{ route('teams.index') }}" class="inline-flex items-center justify-center text-neutral-900 dark:text-gray-100 py-2 px-4 hover:underline">&larr; Retour</a>
        </div>
    </x-slot>

    <div class="flex justify-center my-8">
        <div class="w-full px-6 pb-6 xl:w-8/12">
            <div class="p-6 bg-white shadow rounded">
                <form action="{{ route('teams.update', $team->id) }}" method="post">
                    @csrf
                    @method('PUT')


                    <div class="mb-4">
                        <label for="name" class="block text-neutral-900 text-sm font-bold mb-2">Nom de l'équipe :</label>
                        <input type="text" name="name" value="{{ $team->name }}" class="w-full block shadow-sm text-sm focus:ring-neutral-900 focus:border-neutral-900 border-neutral-300 rounded-md">
                        @error('name')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="category" class="block text-neutral-900 text-sm font-bold mb-2">Catégorie :</label>
                        <input type="text" name="category" value="{{ $team->category }}" class="w-full block shadow-sm text-sm focus:ring-neutral-900 focus:border-neutral-900 border-neutral-300 rounded-md">
                        @error('category')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="add_users" class="block text-neutral-900 text-sm font-bold mb-2">Ajouter des utilisateurs :</label>
                        <select name="add_users[]" multiple aria-label="Add_users" id="add_users" class="overflow-x-auto block w-full shadow-sm focus:ring-neutral-900 focus:border-neutral-900 border-neutral-300 rounded-md">
                            @foreach ($allUsers->diff($team->users) as $user)
                                <option value="{{ $user->id }}">
                                    {{ $user->firstname }} {{ $user->lastname }} - {{ implode(', ', $user->getRoleNames()->toArray()) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="remove_users" class="block text-neutral-900 text-sm font-bold mb-2">Supprimer des utilisateurs :</label>
                        <select name="remove_users[]" multiple class="overflow-x-auto block w-full shadow-sm focus:ring-neutral-900 focus:border-neutral-900 border-neutral-300 rounded-md">
                            @foreach ($team->users as $user)
                                <option value="{{ $user->id }}">
                                    {{ $user->firstname }} {{ $user->lastname }} - Rôle: {{ implode(', ', $user->getRoleNames()->toArray()) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-center justify-between">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            Mettre à jour
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
