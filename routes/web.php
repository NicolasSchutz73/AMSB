<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('index');
})->name('app_home');

Route::get('/inscription', [AuthController::class, 'showRegistrationForm']);
Route::post('/inscription', [AuthController::class, 'register'])->name('register');

Route::get('/connexion', [AuthController::class, 'showLoginForm']);
Route::post('/connexion', [AuthController::class, 'login'])->name('login');

Route::post('/update-profile', [AuthController::class, 'updateProfile'])->name('updateProfile');

Route::get('/profil', function () {
    return view('profil');
})->name('profil');

Route::get('/deconnexion', [AuthController::class, 'logout'])->name('deconnexion');
