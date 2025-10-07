<?php

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

// end-user private updates (per employee)
Broadcast::channel('users.{employeeId}', function ($user, $employeeId) {
    return optional($user->Employee)->id == (int) $employeeId;
});

// signatory badge updates (only if may permission)
Broadcast::channel('signatories', function ($user) {
    return $user->can('acceptemployee', \App\Models\Leave::class);
});