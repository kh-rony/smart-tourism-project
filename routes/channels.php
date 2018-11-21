<?php

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


Broadcast::channel('App.Model.User.{slug}', function ($user, $slug) {
     return $user->slug === $slug;
});


Broadcast::channel('forum', function () {
    return true;
});

Broadcast::channel('forum.{id}', function () {
    return true;
});

Broadcast::channel('conversation.{id}', function ($user, $id) {
    $conversation = \App\Model\Messaging\Conversation::whereId($id)->with('users')->first();
    return $conversation->users->contains($user);
});

Broadcast::channel('loggedUser', function ($user) {
    return $user;
});