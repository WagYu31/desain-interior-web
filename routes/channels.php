<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\User;
use Illuminate\Support\Facades\Log;

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

Broadcast::channel('App.Models.User.{id}', function (User $user, int $id) {
    return (int) $user->id === $id;
});

// Contoh channel publik jika diperlukan (jarang untuk notifikasi user-spesifik)
 Broadcast::channel('public-notifications', function () {
     return true;
 });

// Jika Anda ingin channel spesifik untuk admin, bisa seperti ini:
Broadcast::channel('admin-notifications', function (User $user) {
    return $user->hasRole('admin'); // Hanya admin yang bisa listen
});