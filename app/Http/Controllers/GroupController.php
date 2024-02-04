<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Group;
use App\Models\User;

class GroupController extends Controller
{
    // ...

    public function store(Request $request)
    {
        // Valider la requête
        $request->validate([
            'groupName' => 'required|string|max:255',
            'userIds' => 'required|array',
            'userIds.*' => 'exists:users,id' // Assure-toi que les IDs d'utilisateurs existent
        ]);

        // Créer un nouveau groupe
        $group = new Group();
        $group->name = $request->input('groupName');
        $group->save();

        // Associer les utilisateurs sélectionnés au groupe
        $group->users()->sync($request->input('userIds'));

        // Retourner une réponse, par exemple
        return response()->json(['success' => 'Groupe créé avec succès', 'group' => $group]);
    }


    public function getUserGroups(Request $request)
    {
        $user = $request->user();
        $groups = $user->groups; // Assure-toi que la relation 'groups' est définie dans le modèle User

        return response()->json(['groups' => $groups]);
    }

    public function getMessages(Group $group)
    {
        $messages = $group->messages()->with('user')->get();

        // Transformer les messages pour inclure les détails de l'utilisateur
        $transformedMessages = $messages->map(function ($message) {
            return [
                'id' => $message->id,
                'content' => $message->content,
                'user_id' => $message->user->id, // Récupération de l'ID de l'utilisateur
                'user_firstname' => $message->user->firstname, // Récupération du prénom de l'utilisateur
                'user_lastname' => $message->user->lastname, // Récupération du nom de l'utilisateur
            ];
        });

        return response()->json(['messages' => $transformedMessages]);
    }



    public function chatRoom()
    {
        $users = User::all(); // Déjà présent
        $groups = Group::all(); // Récupérer tous les groupes ou filtrer selon la logique de votre application

        return view('chatRoom', ['users' => $users, 'groups' => $groups]);
    }

    public function checkPrivateGroup($userOneId, $userTwoId)
    {
        // Trouver un groupe où seulement ces deux utilisateurs sont présents et qui suit le modèle de nommage spécifique pour les conversations privées.
        $groupName = "Private_{$userOneId}_{$userTwoId}";
        $group = Group::where('name', $groupName)->withCount('users')
            ->having('users_count', '=', 2)
            ->first();

        if ($group) {
            // Un groupe avec exactement ces deux utilisateurs et le nom spécifique a été trouvé
            return response()->json(['groupId' => $group->id, 'groupName' => $group->name]);
        } else {
            // Aucun groupe trouvé avec exactement ces deux utilisateurs et le nom spécifique
            return response()->json(['groupId' => null]);
        }
    }



}


