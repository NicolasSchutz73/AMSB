<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SearchUserController;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\CalendarController;

// Routes d'accueil
Route::get('/', function () {
    return view('welcome');
});

// Tableau de bord
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


// Recherche d'utilisateurs
Route::get('/search-user', function () {
    return view('searchUser.index');
})->middleware(['auth', 'verified'])->name('searchUser.index');

// Routes de gestion de profil
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Routes de page user
Route::get('/search-user', [SearchUserController::class, 'index'])->name('searchUser.index');
Route::get('/user/{id}', [SearchUserController::class, 'show'])->name('user.show');

// Routes de la messagerie et des groupes
Route::middleware(['auth', 'verified'])->group(function () {
    // Salle de chat
    Route::get('/chat-room', [GroupController::class, 'chatRoom'])->name('chat-room');
    Route::get('/chat-room-users', [SearchUserController::class, 'chatRoomUsers'])->name('chat-room-users');

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

// Routes de gestion des rôles et utilisateurs
Route::resources([
    'roles' => RoleController::class,
    'users' => UserController::class,
    'searchUser' => SearchUserController::class,
    'chat' => ChatController::class,
]);

// Routes de gestion des équipes
Route::resource('teams', TeamController::class);

// Routes de l'équipe d'un utilisateur
Route::middleware(['auth'])->group(function () {
    Route::get('/my-teams', [TeamController::class, 'showUserTeams'])->name('my.teams');
});

// Routes des notifications
Route::get('/notification', function () {
    return view('notification');
})->middleware(['auth', 'verified'])->name('notification');

Route::post('/save-token', [HomeController::class, 'saveToken'])->name('save-token');
Route::post('/send-notification', [HomeController::class, 'sendNotification'])->name('send.notification');

// Routes supplémentaires et API
Route::get('/userinfo', [SearchUserController::class, 'getUserInfo'])->middleware('auth');

Route::get('/api/users', [SearchUserController::class, 'apiIndex'])->middleware('auth');
Route::get('/usersjson', [SearchUserController::class, 'apiIndex']); // À déplacer dans api.php pour une réponse JSON

Route::get('/files/{filename}', 'FileController@show');

Route::get('/calendar', [CalendarController::class, 'show'])->name('calendar');


// Authentification
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
