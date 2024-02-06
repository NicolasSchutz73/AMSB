<?php

use App\Http\Controllers\{
    ChatController,
    ProfileController,
    HomeController,
    GroupController,
    ConversationController,
    RoleController,
    UserController
};
use Illuminate\Support\Facades\Route;

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

/*
|--------------------------------------------------------------------------
| Authentification
|--------------------------------------------------------------------------
|
| Inclusion des routes d'authentification fournies par Laravel Breeze.
|
*/

require __DIR__.'/auth.php';
