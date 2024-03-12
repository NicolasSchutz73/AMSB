<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\GroupNotificationSettingController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\TeamController;

/*
|--------------------------------------------------------------------------
| Routes d'accueil
|--------------------------------------------------------------------------
|
| Ces routes sont destinées à l'affichage des pages principales de l'application.
|
*/

// Page d'accueil
Route::get('/', function () {
    return view('welcome');
});

// Tableau de bord - Accessible uniquement aux utilisateurs authentifiés et vérifiés
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Calendrier - Accessible uniquement aux utilisateurs authentifiés et vérifiés
Route::get('/calendar', function () {
    return view('calendar');
})->middleware(['auth', 'verified'])->name('calendar');

/*
|--------------------------------------------------------------------------
| Routes de gestion de profil
|--------------------------------------------------------------------------
|
| Ces routes permettent à l'utilisateur de modifier et supprimer son profil.
|
*/

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Routes de la messagerie et des groupes
|--------------------------------------------------------------------------
|
| Ces routes sont utilisées pour la gestion des conversations, des groupes et de la messagerie.
|
*/

Route::middleware(['auth', 'verified'])->group(function () {
    // Salle de chat
    Route::get('/chat-room', [GroupController::class, 'chatRoom'])->name('chat-room');
    Route::get('/chat-room-users', [UserController::class, 'chatRoomUsers'])->name('chat-room-users');

    // Groupes et conversations
    Route::resource('groups', GroupController::class);
    Route::resource('conversations', ConversationController::class);

    // Messagerie dans un groupe
    Route::post('/group-chat/{groupId}/send', [ChatController::class, 'store']);
    Route::get('/group-chat/{group}/messages', [GroupController::class, 'getMessages']);

    // Utilisateurs dans les groupes
    Route::get('/user-groups', [GroupController::class, 'getUserGroups'])->name('user-groups');

    // Vérification de groupe privé entre deux utilisateurs
    Route::get('/check-group/{userOneId}/{userTwoId}', [GroupController::class, 'checkPrivateGroup']);


    // Création de groupe
    Route::post('/create-group', [GroupController::class, 'store']);

    Route::post('/api/groups/{group}/update-last-visited', [GroupController::class, 'updateLastVisitedAt']);
    Route::post('/api/groups/{groupId}/reset-unread-messages', [GroupController::class, 'resetUnreadMessages']);

});

/*
|--------------------------------------------------------------------------
| Routes de gestion des rôles et utilisateurs
|--------------------------------------------------------------------------
|
| Ces routes permettent la gestion des rôles et des utilisateurs de l'application.
|
*/

Route::resources([
    'roles' => RoleController::class, // Gestion des rôles
    'users' => UserController::class, // Gestion des utilisateurs
    'chat' => ChatController::class, // Gestion de la messagerie
]);

/*
|--------------------------------------------------------------------------
| Routes de gestion des équipes
|--------------------------------------------------------------------------
|
| Ces routes permettent la gestion des équipes
| Laravel générera automatiquement les routes nécessaires pour le CRUD des équipes.
| GET /teams : Affiche la liste des équipes (index dans le contrôleur).
| GET /teams/create : Affiche le formulaire de création d'équipe (create dans le contrôleur).
| POST /teams : Traite la création d'une nouvelle équipe (store dans le contrôleur).
| GET /teams/{team} : Affiche les détails d'une équipe spécifique (show dans le contrôleur).
| GET /teams/{team}/edit : Affiche le formulaire de modification d'équipe (edit dans le contrôleur).
| PUT/PATCH /teams/{team} : Traite la modification d'une équipe (update dans le contrôleur).
| DELETE /teams/{team} : Supprime une équipe (destroy dans le contrôleur).
*/

Route::resource('teams', TeamController::class);

/*
|--------------------------------------------------------------------------
| Routes de l'équipe d'un utilisateur
|--------------------------------------------------------------------------
| Cette route est utilisées pour qu'un utilisateur puisse voir son équipe.
|
*/

Route::middleware(['auth'])->group(function () {
    Route::get('/my-teams', [TeamController::class, 'showUserTeams'])->name('my.teams');
});


/*
|--------------------------------------------------------------------------
    | Routes des notifications
|--------------------------------------------------------------------------
|
| Ces routes sont utilisées pour des fonctionnalités de notifications.
|
*/

Route::get('/notification', function () {
    return view('notification');
})->middleware(['auth', 'verified'])->name('notification');

Route::post('/save-token', [App\Http\Controllers\HomeController::class, 'saveToken'])->name('save-token');
Route::post('/send-notification', [App\Http\Controllers\HomeController::class, 'sendNotification'])->name('send.notification');

Route::post('/toggle-group-notification', [GroupNotificationSettingController::class, 'toggleNotificationGroup']);
Route::get('/group/{groupId}/user/{userId}/notification-status', [GroupNotificationSettingController::class, 'getNotificationStatus']);

/*
|--------------------------------------------------------------------------
| Routes supplémentaires et API
|--------------------------------------------------------------------------
|
| Ces routes sont utilisées pour des fonctionnalités supplémentaires et l'accès API.
|
*/

// Accès à l'information de l'utilisateur
Route::get('/userinfo', [UserController::class, 'getUserInfo'])->middleware('auth');

// Endpoint API pour lister les utilisateurs - Utiliser dans api.php pour une réponse JSON
Route::get('/api/users', [UserController::class, 'apiIndex'])->middleware('auth');
Route::get('/usersjson', [UserController::class, 'apiIndex']); // À déplacer dans api.php pour une réponse JSON


Route::get('/files/{filename}', 'FileController@show');

/*
|--------------------------------------------------------------------------
| Authentification
|--------------------------------------------------------------------------
|
| Inclusion des routes d'authentification fournies par Laravel Breeze.
|
*/

Route::get('storage/profile-photos/{filename}', function ($filename) {
    $path = '/ASMB/' . $filename;

    if (Storage::disk('ftp')->exists($path)) {
        $fileContent = Storage::disk('ftp')->get($path);
        $mimeType = Storage::disk('ftp')->mimeType($path);

        return response($fileContent)->header('Content-Type', $mimeType);
    }

    abort(404);
})->where('filename', '.*');

require __DIR__.'/auth.php';
