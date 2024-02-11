<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="mb-1 font-semibold text-xl text-neutral-900 dark:text-gray-100">Détails de l'équipe</h2>
            <a href="{{ route('teams.index') }}" class="inline-flex items-center justify-center text-neutral-900 dark:text-gray-100 py-2 px-4 hover:underline">&larr; Retour</a>
        </div>
    </x-slot>

    <div class="flex justify-center my-8">
        <div class="w-full px-6 xl:w-8/12">
            <div class="p-6 bg-white shadow rounded">
                <span class="block text-neutral-900 font-bold mb-4">Nom de l'équipe :
                    <span class="w-full block text-neutral-600 font-medium py-1"> {{ $team->name }}</span>
                </span>
                <span class="block text-neutral-900 font-bold mb-4">Catégorie :
                    <span class="w-full block text-neutral-600 font-medium py-1"> {{ $team->category }}</span>
                </span>

                <h3 class="font-bold text-lg mb-4">Joueurs de l'équipe :</h3>

                @forelse ($users->filter(function($user) {
                    return $user->hasRole('joueur');
                }) as $joueur)
                    <div>
                        <span class="mb-2">{{ $joueur->firstname }} {{ $joueur->lastname }}</span>
                    </div>
                @empty
                    <p>Aucun joueur dans cette équipe pour le moment.</p>
                @endforelse

                <h3 class="font-bold text-lg my-4">
                    @if ($coaches->count() === 1)
                        Entraineur de l'équipe :
                    @elseif ($coaches->count() > 1)
                        Entraineurs de l'équipe :
                    @else
                        Aucun entraineur dans cette équipe pour le moment
                    @endif
                </h3>

                @forelse ($coaches as $coach)
                    <div>
                        <span class="mb-2">{{ $coach->firstname }} {{ $coach->lastname }}</span>
                    </div>
                @empty
                    <p>Aucun entraineur dans cette équipe pour le moment.</p>
                @endforelse

                <h3 class="font-bold text-lg my-4">Parents de l'équipe :</h3>

                @forelse ($users->filter(function($user) {
                    return $user->hasRole('parents');
                }) as $parent)
                    <div>
                        <span>
                            @forelse ($parent->getRoleNames() as $role)
                                <span class="capitalize">{{ $role }} :</span>
                            @empty
                                Aucun rôle assigné
                            @endforelse
                        </span>
                        <span class="mb-2">{{ $parent->firstname }} {{ $parent->lastname }}</span>
                    </div>
                @empty
                    <p>Aucun parent dans cette équipe pour le moment.</p>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
