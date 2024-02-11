<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="mb-1 font-semibold text-xl text-neutral-900 dark:text-gray-100">
                @if (count($teamDetails) > 1)
                    Mes équipes
                @else
                    Mon équipe
                @endif
            </h2>
            <a href="{{ route('dashboard') }}" class="inline-flex items-center justify-center text-neutral-900 dark:text-gray-100 py-2 px-4 hover:underline">&larr; Retour</a>
        </div>
    </x-slot>

    <div class="flex justify-center my-8">
        <div class="w-full px-6 xl:w-8/12">
            <div class="p-6 bg-white shadow rounded">
                @if (empty($teamDetails))
                    <p>Vous n'êtes pas encore associé à une équipe.</p>
                @else
                    @foreach ($teamDetails as $teamDetail)
                        <div class="mb-4">
                            <span class="block text-neutral-900 font-bold mb-2">Nom de l'équipe :</span>
                            <span class="w-full block text-neutral-600 font-medium">{{ $teamDetail['team']->name }}</span>
                        </div>

                        <div class="mb-4">
                            <span class="block text-neutral-900 font-bold mb-2">Catégorie :</span>
                            <span class="w-full block text-neutral-600 font-medium">{{ $teamDetail['team']->category }}</span>
                        </div>

                        <h3 class="font-bold text-lg mb-4">Joueurs de l'équipe :</h3>
                        @forelse ($teamDetail['users'] as $user)
                            @if ($user->hasRole('joueur'))
                                <div>
                                    <span class="mb-2">{{ $user->firstname }} {{ $user->lastname }}</span>
                                </div>
                            @endif
                        @empty
                            <p>Aucun joueur dans cette équipe pour le moment.</p>
                        @endforelse

                        <h3 class="font-bold text-lg my-4">
                            @if ($teamDetail['coaches']->count() === 1)
                                Entraineur de l'équipe :
                            @elseif ($teamDetail['coaches']->count() > 1)
                                Entraineurs de l'équipe :
                            @else
                                Entraineur de l'équipe :
                            @endif
                        </h3>

                        @forelse ($teamDetail['coaches'] as $coach)
                            <div>
                                <span class="mb-2">{{ $coach->firstname }} {{ $coach->lastname }}</span>
                            </div>
                        @empty
                            <p>Aucun entraîneur dans cette équipe pour le moment.</p>
                        @endforelse

                        <h3 class="font-bold text-lg my-4">Parents de l'équipe :</h3>
                            @forelse ($teamDetail['users'] as $user)
                                @if ($user->hasRole('parents'))
                                    <div>
                                        <span class="mb-2">{{ $user->firstname }} {{ $user->lastname }}</span>
                                    </div>
                               @endif
                            @empty
                                <p>Aucun parent dans cette équipe pour le moment.</p>
                            @endforelse
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
