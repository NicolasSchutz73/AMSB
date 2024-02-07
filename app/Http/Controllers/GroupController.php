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

        // Préparez la requête de base pour les groupes
        $groupsQuery = $user->groups()->with(['users' => function ($query) {
            // Qualifiez 'id' avec le nom de la table 'users'
            $query->select('users.id', 'users.firstname', 'users.lastname');
        }]);

        if ($type) {
            // Filtrer les groupes en fonction du type si le paramètre type est présent
            $groupsQuery->where('type', $type);
        }

        // Récupérez les groupes avec les utilisateurs préchargés
        $groups = $groupsQuery->get();

        // Transformez les groupes pour inclure les informations des membres
        $groups->transform(function ($group) {
            $group->members = $group->users->map(function ($user) {
                return [
                    'id' => $user->id,
                    'firstname' => $user->firstname,
                    'lastname' => $user->lastname,
                ];
            });
            unset($group->users); // Supprimez la relation users pour éviter la redondance
            return $group;
        });

        return response()->json(['groups' => $groups]);
    }



    public function getGroupMembers($groupId)
    {
        // Trouve le groupe et charge ses utilisateurs associés
        $group = Group::with('users')->find($groupId);

        // Vérifie si le groupe a été trouvé
        if (!$group) {
            return response()->json(['error' => 'Groupe non trouvé'], 404);
        }

        // Préparation des données des membres pour la réponse
        // Ici, pas besoin de mapper, on pourrait simplement renvoyer les utilisateurs directement,
        // mais la méthode map est utilisée pour filtrer les attributs si nécessaire.
        $members = $group->users->map(function ($user) {
            return [
                'id' => $user->id,
                'firstname' => $user->firstname,
                'lastname' => $user->lastname,
            ];
        });

        // Retourne la liste des membres si le groupe est trouvé
        return response()->json(['members' => $members]);
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
                'groupName' => $group->name,
                'userTwoId' => $userTwoId,
                'userOneId' => $userOneId
            ]);
        }

        // Si aucun groupe commun seulement composé de ces deux utilisateurs n'est trouvé, retournez null
        return response()->json([
            'groupId' => null,
            'groupName' => null,
            'userTwoId' => null,
            'userOneId' => null
        ]);
    }

    public function sendNotificationGroup(Request $request)
    {
        // Valider la requête
        $validatedData = $request->validate([
            'groupId' => 'required|integer',
            'message' => 'required|string',
            'id_sender' =>'required|integer'
        ]);

        // Récupérer le groupe par son ID
        $group = Group::find($validatedData['groupId']);

        // Vérifier si le groupe existe
        if (!$group) {
            return response()->json(['error' => 'Group not found'], 404);
        }

        // Récupérer les tokens FCM des membres du groupe
        $firebaseTokens = $group->users()
            ->whereNotNull('device_token')
            ->where('users.id', '!=', $validatedData['id_sender']) // Exclure l'expéditeur
            ->pluck('device_token')
            ->all();
        if (empty($firebaseTokens)) {
            return response()->json(['error' => 'No members with registered devices in this group'], 404);
        }

        // Configuration de la clé API du serveur FCM et des données de la notification
        $SERVER_API_KEY = 'AAAAoXM2Djo:APA91bG4FIc9ClN16o2rpZ6jByJPYXqXwlpBW_GeRcgiomMslDKkgG8MszzC8eI36-KKb9iQFzL567Bk29xA2ZfadgNTg5c8EPeKxF4E8dcOyLyYtND_3Hvl4Y8seQDrcocnmpzBZxml';
        $data = [
            "registration_ids" => $firebaseTokens,
            "notification" => [
                "title" => "New message in group {$group->name}",
                "body" => $validatedData['message'],
                "content_available" => true,
                "priority" => "high",
            ]
        ];

        // Envoyer la notification via FCM
        $dataString = json_encode($data);
        $headers = [
            'Authorization: key=' . $SERVER_API_KEY,
            'Content-Type: application/json',
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

        $response = curl_exec($ch);

        if ($response === false) {
            $error = curl_error($ch);
            curl_close($ch);
            return response()->json(['error' => "cURL Error: " . $error], 500);
        }

        curl_close($ch);
        return response()->json([
            'success' => 'Notifications sent successfully',
            'message' => $data
            ]);
    }




}


