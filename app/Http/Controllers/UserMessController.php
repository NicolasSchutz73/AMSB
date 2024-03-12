<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserMessController extends Controller
{

    /**
     * Récupère les détails d'un utilisateur par son ID et retourne les données en JSON.
     *
     * @param int $id L'ID de l'utilisateur.
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserDetailsById($id)
    {
        $user = User::findOrFail($id);

        return response()->json($user);
    }
    /**
     * Affiche la liste des utilisateurs pour users
     * @return View
     */
    public function index($monid): View
    {
        // Utilisation de $this pour appeler une méthode dans la même classe
        // Mais dans ce cas, vous devriez probablement accéder directement au modèle
        $user = User::findOrFail($monid);
        return view('usersMess.show', [
            'user' => $user
        ]);
    }

    /**
     * Stocke un nouvel utilisateur dans la base de données.
     * @param StoreUserRequest $request
     * @return RedirectResponse
     */
    public function store(StoreUserRequest $request): RedirectResponse
    {
        $input = $request->all();
        $input['password'] = Hash::make($request->password);

        $user = User::create($input);
        $user->assignRole($request->roles);

        return redirect()->route('usersMess.index');
    }

    /**
     * Affiche les détails de l'utilisateur spécifié pour users
     * @param User $user
     * @return View
     */
    public function show(User $user): View
    {

        return view('usersMess.show', [
            'user' => $user
        ]);
    }



//    /**
//     * Récupère les détails d'un utilisateur par son ID et retourne les données en JSON.
//     *
//     * @param int $id L'ID de l'utilisateur.
//     * @return \Illuminate\Http\JsonResponse
//     */
//    public function getUserDetailsById($id)
//    {
//        $user = User::findOrFail($id);
//
//        return response()->json($user);
//    }


    public function chatRoomUsers()
    {
        $users = User::all(); // Récupère les données nécessaires pour la fonctionnalité de chat
        return view('chatRoom', ['users' => $users]); // Retourne une vue différente
    }

    // Méthode pour renvoyer des données JSON
    public function apiIndex()
    {
        $users = User::all(); // Ou toute autre logique pour obtenir les utilisateurs
        return response()->json($users);
    }

    // Dans votre controller
    public function getUserInfo()
    {
        // Récupération de l'utilisateur actuellement connecté
        $user = auth()->user();

        // Retourne le prénom et le nom sous forme de tableau associatif
        return response()->json([
            'firstname' => $user->firstname,
            'lastname' => $user->lastname,
            'id' => $user->id,
        ]);
    }



}
