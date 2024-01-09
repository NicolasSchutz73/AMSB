<!-- TODO : Modifier la mise en page -->

<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <label for="User Information" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                User Information
            </label>
            <div>
                <a href="{{ route('users.index') }}" class="bg-blue-500 text-white py-1 px-3 rounded text-sm">&larr; Back</a>
            </div>
        </div>
    </x-slot>

    <div class="p-6">

        <div class="mb-4">
            <label for="firstname" class="text-gray-500 dark:text-gray-400 block text-md-end text-start">
                <strong>Firstname :</strong>
            </label>
            <div class="mt-1 text-gray-500 dark:text-gray-40">
                {{ $user->firstname }}
            </div>
        </div>

        <div class="mb-4">
            <label for="lastname" class="text-gray-500 dark:text-gray-400 block text-md-end text-start">
                <strong>Lastname :</strong>
            </label>
            <div class="mt-1 text-gray-500 dark:text-gray-40">
                {{ $user->lastname }}
            </div>
        </div>

        <div class="mb-4">
            <label for="email" class="text-gray-500 dark:text-gray-400 block text-md-end text-start"><strong>Email Address:</strong></label>
            <div class="mt-1 text-gray-500 dark:text-gray-40">
                {{ $user->email }}
            </div>
        </div>

        <div class="mb-4">
            <label for="roles" class="text-gray-500 dark:text-gray-400 block text-md-end text-start"><strong>Roles:</strong></label>
            <div class="mt-1">
                @forelse ($user->getRoleNames() as $role)
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">{{ $role }}</span>
                @empty
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
