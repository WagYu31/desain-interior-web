<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\FcmTokenController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// ======================================================================
// TAMBAHKAN ROUTE INI
// ======================================================================
Route::post('/fcm-token', [FcmTokenController::class, 'updateFcmToken'])->middleware('auth:sanctum');