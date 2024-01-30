<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}" >
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="firstname" :value="__('Prénom')" />
            <x-text-input id="firstname" name="firstname" type="text" class="mt-1 block w-full" :value="old('firstname', $user->firstname)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('firstname')" />
        </div>

        <div>
            <x-input-label for="lastname" :value="__('Nom')" />
            <x-text-input id="lastname" name="lastname" type="text" class="mt-1 block w-full" :value="old('lastname', $user->lastname)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('lastname')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Adresse e-mail')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800 dark:text-gray-200">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div>
            <x-input-label for="description" :value="__('Description')" />
            <x-text-input id="description" name="description" type="text" class="mt-1 block w-full" :value="old('description', $user->description)" autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('description')" />
        </div>

        <div>
            <x-input-label for="emergency" :value="__('Contact(s) d\'urgence')" />
            <x-text-input id="emergency" name="emergency" type="text" class="mt-1 block w-full" :value="old('emergency', $user->emergency)" autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('emergency')" />
        </div>

        <div class="mb-4">
            <label for="profile_photo" class="text-gray-500 dark:text-gray-400 block text-md-end text-start">Photo de profil</label>
            <div class="mb-4">
                <label for="profile_photo" class="block text-sm font-medium text-gray-700">Choisir une photo de profil</label>
                <input type="file" class="mt-1 p-2 border rounded-md w-full focus:outline-none focus:ring focus:border-blue-300" id="profile_photo" name="profile_photo">
            </div>
        </div>

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

        <div class="mb-4">
            <label for="document" class="text-gray-500 dark:text-gray-400 block text-md-end text-start">Document</label>
            <div class="mb-4">
                <label for="document" class="block text-sm font-medium text-gray-700">Choisir un document</label>
                <input type="file" class="mt-1 p-2 border rounded-md w-full focus:outline-none focus:ring focus:border-blue-300" id="document" name="document">
            </div>
        </div>

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

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 dark:text-gray-400"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
