<!-- TODO : Modifier la mise en page -->

<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <label for="User Information" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                Information de l'utilisateur
            </label>
            <div>
                <a href="{{ route('users.index') }}" class="bg-blue-500 text-white py-1 px-3 rounded text-sm">&larr; Retour</a>
            </div>
        </div>
    </x-slot>

    <div class="p-6">
        <div class="mb-4">
            @php
                    $imageUrl = "http://mcida.eu/AMSB/profile/" . $user->id . ".jpg";
                    $headers = get_headers($imageUrl);
            @endphp

            @if (strpos($headers[0], '200') !== false)
                <img src="{{ $imageUrl }}" alt="Photo de profil" class="img-fluid rounded" style="width: 150px; height: 150px; border: 2px solid #fff; clip-path:ellipse(50% 50%);">
            @else
                <div class="mt-1 text-red-500 dark:text-red-40">
                    Aucune photo de profil disponible
                </div>
            @endif
        </div>
        <div class="mb-4">
            @php
                $documentUrl = "http://mcida.eu/AMSB/documents/" . $user->id . ".pdf";
                $headers = get_headers($documentUrl);
            @endphp

            @if (strpos($headers[0], '200') !== false)
                <!-- Affichez le document comme vous le souhaitez (par exemple, un lien de téléchargement) -->
                <a href="{{ $documentUrl }}" target="_blank" class="inline-block px-4 py-2 mt-2 text-base font-semibold text-white bg-blue-500 rounded-md hover:bg-blue-600 focus:outline-none focus:shadow-outline-blue focus:border-blue-700 active:bg-blue-800 transition duration-150 ease-in-out">
                    Télécharger le document
                </a>
            @else
                <div class="mt-1 text-red-500 dark:text-red-40">
                    Aucun document disponible
                </div>
            @endif
        </div>
        <div class="mb-4">
            <label for="firstname" class="text-gray-500 dark:text-gray-400 block text-md-end text-start">
                <strong>Prénom :</strong>
            </label>
            <div class="mt-1 text-gray-500 dark:text-gray-40">
                {{ $user->firstname }}
            </div>
        </div>

        <div class="mb-4">
            <label for="lastname" class="text-gray-500 dark:text-gray-400 block text-md-end text-start">
                <strong>Nom :</strong>
            </label>
            <div class="mt-1 text-gray-500 dark:text-gray-40">
                {{ $user->lastname }}
            </div>
        </div>

        <div class="mb-4">
            <label for="email" class="text-gray-500 dark:text-gray-400 block text-md-end text-start"><strong>E-mail :</strong></label>
            <div class="mt-1 text-gray-500 dark:text-gray-40">
                {{ $user->email }}
            </div>
        </div>

        <div class="mb-4">
            <label for="roles" class="text-gray-500 dark:text-gray-400 block text-md-end text-start"><strong>Description :</strong></label>
            @isset($user->description)
                <div class="mt-1 text-gray-500 dark:text-gray-40">
                    {{ $user->description }}
                </div>
            @else
                <div class="mt-1 text-red-500 dark:text-red-40">
                    Aucune description
                </div>
            @endisset
        </div>

        <div class="mb-4">
            <label for="roles" class="text-gray-500 dark:text-gray-400 block text-md-end text-start"><strong>Contact(s) d'urgence :</strong></label>
            @isset($user->emergency)
                <div class="mt-1 text-gray-500 dark:text-gray-40">
                    {{ $user->emergency }}
                </div>
            @else
                <div class="mt-1 text-red-500 dark:text-red-40">
                    Aucune contact d'urgence
                </div>
            @endisset
        </div>


    </div>
</x-app-layout>
