<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/calendar', function () {
    return view('calendar');
})->middleware(['auth', 'verified'])->name('calendar');

Route::get('/chat-room', function () {
    return view('chatRoom');
})->middleware(['auth', 'verified'])->name('chat-room');

// Routes des ressources pour les rôles et utilisateurs et chat
Route::resources([
    'roles' => RoleController::class, // Ressources pour la gestion des rôles
    'users' => UserController::class, // Ressources pour la gestion des utilisateurs
    'chat' => ChatController::class,
]);


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::resource('groups', GroupController::class);
    Route::resource('conversations', ConversationController::class);
    Route::post('/group-chat/{groupId}/send', [ChatController::class, 'store']);
    Route::get('/groups/{group}/messages', [GroupController::class, 'getMessages']);
});

Route::get('/chat-room-users', [UserController::class, 'chatRoomUsers'])
    ->middleware(['auth', 'verified'])
    ->name('chat-room-users');


// Utilise ce code dans routes/api.php pour une réponse JSON
Route::get('/usersjson', [UserController::class, 'apiIndex']); // y déplacer dans api.php
Route::post('/create-group', [GroupController::class, 'store'])->middleware('auth');

require __DIR__.'/auth.php';
