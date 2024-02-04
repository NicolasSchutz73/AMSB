<?php

use App\Http\Controllers\GroupController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->group(static function () : void {
    Route::get('events', \App\Http\Controllers\Api\Events\IndexController::class)->name('events');
    Route::put('subscribe', \App\Http\Controllers\Api\Courses\SubscribeController::class)->name('subscribe');
    Route::get('/groups/{group}/messages', [GroupController::class, 'getMessages']);

});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->get('/user-groups', [GroupController::class, 'getUserGroups']);

// web.php ou api.php

Route::get('/check-group/{userOneId}/{userTwoId}', [GroupController::class, 'checkPrivateGroup']);

