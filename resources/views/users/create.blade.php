<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <label for="Add New Role" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                Add New User
            </label>
            <div>
                <a href="{{ route('users.index') }}" class="bg-blue-500 text-white py-1 px-3 rounded text-sm">&larr; Back</a>
            </div>
        </div>
    </x-slot>

    <div class="p-6">
        <form action="{{ route('users.store') }}" method="post" enctype="multipart/form-data">
            @csrf

            <div class="mb-4">
                <label for="firstname" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 block text-md-end text-start">Firstname</label>
                <div>
                    <input type="text" class="w-full px-3 py-2 border rounded-lg @error('firstname') border-red-500 @enderror" id="firstname" name="firstname" value="{{ old('firstname') }}">
                    @if ($errors->has('firstname'))
                        <span class="text-red-500">{{ $errors->first('firstname') }}</span>
                    @endif
                </div>
            </div>

            <div class="mb-4">
                <label for="lastname" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 block text-md-end text-start">Lastname</label>
                <div>
                    <input type="text" class="w-full px-3 py-2 border rounded-lg @error('lastname') border-red-500 @enderror" id="lastname" name="lastname" value="{{ old('lastname') }}">
                    @if ($errors->has('lastname'))
                        <span class="text-red-500">{{ $errors->first('lastname') }}</span>
                    @endif
                </div>
            </div>

            <div class="mb-4">
                <label for="email" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 block text-md-end text-start">Email Address</label>
                <div>
                    <input type="email" class="w-full px-3 py-2 border rounded-lg @error('email') border-red-500 @enderror" id="email" name="email" value="{{ old('email') }}">
                    @if ($errors->has('email'))
                        <span class="text-red-500">{{ $errors->first('email') }}</span>
                    @endif
                </div>
            </div>

            <div class="mb-4">
                <label for="password" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 block text-md-end text-start">Password</label>
                <div>
                    <input type="password" class="w-full px-3 py-2 border rounded-lg @error('password') border-red-500 @enderror" id="password" name="password">
                    @if ($errors->has('password'))
                        <span class="text-red-500">{{ $errors->first('password') }}</span>
                    @endif
                </div>
            </div>

            <div class="mb-4">
                <label for="password_confirmation" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 block text-md-end text-start">Confirm Password</label>
                <div>
                    <input type="password" class="w-full px-3 py-2 border rounded-lg" id="password_confirmation" name="password_confirmation">
                </div>
            </div>

            <div class="mb-4">
                <label for="roles" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 block text-md-end text-start">Roles</label>
                <div>
                    <select class="w-full px-3 py-2 border rounded-lg @error('roles') border-red-500 @enderror" multiple aria-label="Roles" id="roles" name="roles[]">
                        @forelse ($roles as $role)
                            @if ($role!='Super Admin')
                                <option value="{{ $role }}" {{ in_array($role, old('roles') ?? []) ? 'selected' : '' }}>
                                    {{ $role }}
                                </option>
                            @else
                                @if (Auth::user()->hasRole('Super Admin'))
                                    <option value="{{ $role }}" {{ in_array($role, old('roles') ?? []) ? 'selected' : '' }}>
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

            <!-- Description Field -->
            <div class="mb-4">
                <label for="description" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 block text-md-end text-start">Description</label>
                <div>
                    <textarea class="w-full px-3 py-2 border rounded-lg @error('description') border-red-500 @enderror" id="description" name="description">{{ old('description') }}</textarea>
                    @if ($errors->has('description'))
                        <span class="text-red-500">{{ $errors->first('description') }}</span>
                    @endif
                </div>
            </div>

            <!-- Emergency Contact Field -->
            <div class="mb-4">
                <label for="emergency" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 block text-md-end text-start">Emergency Contact</label>
                <div>
                    <input type="text" class="w-full px-3 py-2 border rounded-lg @error('emergency') border-red-500 @enderror" id="emergency" name="emergency" value="{{ old('emergency') }}">
                    @if ($errors->has('emergency'))
                        <span class="text-red-500">{{ $errors->first('emergency') }}</span>
                    @endif
                </div>
            </div>

            <div class="mb-4">
                <input type="submit" class="w-full px-3 py-2 bg-blue-500 text-white rounded-lg" value="Add User">
            </div>
        </form>
    </div>
</x-app-layout>
