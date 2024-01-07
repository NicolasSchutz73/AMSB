<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;


class AuthController extends Controller
{
    use ValidatesRequests;

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function register(HttpRequest  $request)
    {
        $email = $request->input('email');
        $emailConf = $request->input('emailConf');
        $password = $request->input('password');
        $passwordConf = $request->input('passwordConf');

        if ($email !== $emailConf) {
            return redirect()->back()->withErrors(['error' => 'Le adresses email et la confirmation d\'adresse email ne sont pas identique '])->withInput();
        }

        if ($password !== $passwordConf) {
            return redirect()->back()->withErrors(['error' => 'Le mots de passe et la confirmation de mot de passe ne sont pas identique '])->withInput();
        }

        // Création de l'utilisateur
        $user = User::create([
            'nom' => $request->input('lastname'),
            'prenom' => $request->input('firstname'),
            'mail' => $email,
            'Mdp' => Hash::make($password),
        ]);

        // redirection vers la page de connexion
        return redirect('/connexion')->with('success', 'Inscription réussie !');
    }

    public function login(HttpRequest $request)
    {
        // recupération des champs rentrer
        $email = $request->input('email');
        $password = $request->input('password');

        // on recupere les valeurs de l'utilisteur avec l'adresse email rentrer
        $user = User::where('mail', $email)->first();

        // on verifie si l'adresse email existe
        if($user != null){
            // on recupere sont mot de passe
            $passwordFromDatabase = $user->Mdp;
            // on verfifie que l'utilisateur existe et que les mot de passe correspond
            // si oui on le redirige vers profil
            if ($user && Hash::check($password, $passwordFromDatabase)) {
                // L'authentification a réussi
                Session::put('ID_user', $user->ID_user);
                Session::put('Nom', $user->Nom);
                Session::put('Prenom', $user->Prenom);
                Session::put('Mail', $user->Mail);
                return redirect('/profil')->with('success', 'Connexion réussie !');
            } else {
                // L'authentification a échoué
                return view('auth.login')->withErrors(['error' => 'Le mot de passe ne correspond pas']);
            }
        }else {
            // L'authentification a échoué
            return view('auth.login')->withErrors(['error' => 'L\'adresse email n\'existe pas' ]);
        }
    }

    public function updateProfile(HttpRequest $request)
    {
        $user = User::find(session('ID_user'));

        if (!$user) {
            return redirect('/profil')->with('error', 'Utilisateur non trouvé.');
        }

        // Mettre à jour les données du profil
        $user->Nom = $request->input('nom');
        $user->Prenom = $request->input('prenom');
        $user->Mail = $request->input('mail');

        Session::forget(['Nom', 'Prenom', 'Mail']);
        Session::put('Nom', $user->Nom);
        Session::put('Prenom', $user->Prenom);
        Session::put('Mail', $user->Mail);

        // Enregistrer les modifications dans la base de données
        $user->save();

        return redirect('/profil')->with('success', 'Profil mis à jour avec succès.');
    }

    public function logout()
    {
        if (session()->has('ID_user')) {
            // Supprimer des variables spécifiques
            //Session::forget(['Name', 'Prenom', 'ID_user']);

            //supprimer toutes les variables de session
            Session::flush();

            return redirect('/connexion')->with('success', 'Vous avez été déconnecté avec succès.');
        }

        // Ajoutez d'autres opérations de déconnexion si nécessaire

        return view('auth.login')->withErrors(['error' => 'Vous n\'étiez pas connecté.']);
    }
}
