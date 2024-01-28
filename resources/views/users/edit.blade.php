<!-- TODO : Modifier la mise en page -->

<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <label for="Edit User" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                Modifier l'utilisateur
            </label>
            <div>
                <a href="{{ route('users.index') }}" class="bg-blue-500 text-white py-1 px-3 rounded text-sm">&larr; Retour</a>
            </div>
        </div>
    </x-slot>

    <div class="p-6">
        <form action="{{ route('users.update', $user->id) }}" method="post">
            @csrf
            @method("PUT")

            <div class="mb-4">
                <label for="firstname" class="text-gray-500 dark:text-gray-400 block text-md-end text-start">Prénom</label>
                <div>
                    <input type="text" class="w-full px-3 py-2 border rounded-lg @error('firstname') border-red-500 @enderror" id="firstname" name="firstname" value="{{ $user->firstname }}">
                    @if ($errors->has('firstname'))
                        <span class="text-red-500">{{ $errors->first('firstname') }}</span>
                    @endif
                </div>
            </div>

            <div class="mb-4">
                <label for="lastname" class="text-gray-500 dark:text-gray-400 block text-md-end text-start">Nom</label>
                <div>
                    <input type="text" class="w-full px-3 py-2 border rounded-lg @error('lastname') border-red-500 @enderror" id="lastname" name="lastname" value="{{ $user->lastname }}">
                    @if ($errors->has('lastname'))
                        <span class="text-red-500">{{ $errors->first('lastname') }}</span>
                    @endif
                </div>
            </div>

            <div class="mb-4">
                <label for="email" class="text-gray-500 dark:text-gray-400 block text-md-end text-start">E-mail</label>
                <div>
                    <input type="email" class="w-full px-3 py-2 border rounded-lg @error('email') border-red-500 @enderror" id="email" name="email" value="{{ $user->email }}">
                    @if ($errors->has('email'))
                        <span class="text-red-500">{{ $errors->first('email') }}</span>
                    @endif
                </div>
            </div>

            <div class="mb-4">
                <label for="password" class="text-gray-500 dark:text-gray-400 block text-md-end text-start">Mot de passe</label>
                <div>
                    <input type="password" class="w-full px-3 py-2 border rounded-lg @error('password') border-red-500 @enderror" id="password" name="password">
                    @if ($errors->has('password'))
                        <span class="text-red-500">{{ $errors->first('password') }}</span>
                    @endif
                </div>
            </div>

            <div class="mb-4">
                <label for="password_confirmation" class="text-gray-500 dark:text-gray-400 block text-md-end text-start">Confirmation du mot de passe</label>
                <div>
                    <input type="password" class="w-full px-3 py-2 border rounded-lg" id="password_confirmation" name="password_confirmation">
                </div>
            </div>

            <div class="mb-4">
                <label for="roles" class="text-gray-500 dark:text-gray-400 block text-md-end text-start">Rôles</label>
                <div>
                    <select class="w-full px-3 py-2 border rounded-lg @error('roles') border-red-500 @enderror" multiple aria-label="Roles" id="roles" name="roles[]">
                        @forelse ($roles as $role)
                            @if ($role!='Super Admin')
                                <option value="{{ $role }}" {{ in_array($role, $userRoles ?? []) ? 'selected' : '' }}>
                                    {{ $role }}
                                </option>
                            @else
                                @if (Auth::user()->hasRole('Super Admin'))
                                    <option value="{{ $role }}" {{ in_array($role, $userRoles ?? []) ? 'selected' : '' }}>
                                        {{ $role }}
                                    </option>
                                @endif
                            @endif
                        @empty
                        @endforelse
                    </select>
                    @if ($errors->has('roles'))
                        <span class="text-red-500">{{ $errors->first('roles') }}</span>
                    @endif
                </div>
            </div>

            <div class="mb-4">
                <input type="submit" class="w-full px-3 py-2 bg-blue-500 text-white rounded-lg" value="Mettre à jour l'utilisateur">
            </div>

        </form>
    </div>
</x-app-layout>
