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
        $request->validate([
            'groupName' => 'required|string|max:255',
            'userIds' => 'required|array',
            'userIds.*' => 'exists:users,id'
        ]);

        $group = new Group();
        $group->name = $request->input('groupName');
        // Définir le type en fonction du nombre d'utilisateurs (par exemple, 'private' pour 2 utilisateurs)
        $group->type = count($request->input('userIds')) === 2 ? 'private' : 'group';
        $group->save();

        $group->users()->sync($request->input('userIds'));

        return response()->json(['success' => 'Groupe créé avec succès', 'group' => $group]);
    }



    public function getUserGroups(Request $request)
    {
        $user = $request->user();

        // Récupérez le type à partir de la requête, s'il est présent
        $type = $request->query('type');

        if ($type) {
            // Filtrer les groupes en fonction du type si le paramètre type est présent
            $groups = $user->groups()->where('type', $type)->get();
        } else {
            // Sinon, retourner tous les groupes de l'utilisateur
            $groups = $user->groups;
        }

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
        // Trouver les groupes communs aux deux utilisateurs.
        $groupsUserOne = Group::whereHas('users', function ($query) use ($userOneId) {
            $query->where('users.id', $userOneId);
        })->pluck('id');

        $group = Group::whereIn('id', $groupsUserOne)
            ->whereHas('users', function ($query) use ($userTwoId) {
                $query->where('users.id', $userTwoId);
            })
            ->withCount('users')
            ->having('users_count', '=', 2)
            ->first();

        // Si un tel groupe est trouvé, retournez les détails, y compris l'ID et le nom du groupe
        if ($group) {
            return response()->json([
                'groupId' => $group->id,
                'groupName' => $group->name
            ]);
        }

        // Si aucun groupe commun seulement composé de ces deux utilisateurs n'est trouvé, retournez null
        return response()->json([
            'groupId' => null,
            'groupName' => null
        ]);
    }



}


