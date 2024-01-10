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
}

