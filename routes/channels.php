<?php

use App\Models\Group;
use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});


Broadcast::channel('users.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('groups.{group}', function ($user, Group $group) {
    return $group->hasUser($user->id);
});

Broadcast::channel('group.{groupId}', function ($user, $groupId) {
    return $user->isMemberOfGroup($groupId);
});



Broadcast::channel('group.{groupId}', function ($user, $groupId) {
    \Log::info("Attempt to access group channel: Group ID - $groupId, User ID - {$user->id}");
    return true; // Temporairement pour tester
});




