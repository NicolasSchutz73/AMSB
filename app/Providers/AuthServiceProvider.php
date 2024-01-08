<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // Utilisation de la façade Gate pour définir des permissions globales
        Gate::before(function ($user, $ability) {
            // Si l'utilisateur a le rôle 'Super Admin',
            // Toutes les vérifications d'autorisations sont automatiquement passées pour cet utilisateur
            return $user->hasRole('Super Admin') ? true : null;
        });
    }
}
