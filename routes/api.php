<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\OutletController;
use App\Http\Controllers\PaketController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\{RedirectIfAuthenticatedApi, CheckJwtToken};

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api');
Route::get('/me', [AuthController::class, 'me'])->middleware('auth:api');

Route::middleware(['auth:api'])->group(function () {
    Route::apiResource('outlets', OutletController::class);
    Route::apiResource('pakets', PaketController::class);
    Route::apiResource('users', UserController::class);
});
