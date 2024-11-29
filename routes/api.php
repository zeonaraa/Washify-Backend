<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\OutletController;
use App\Http\Controllers\PaketController;

// Auth
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('outlets', OutletController::class);
    Route::apiResource('pakets', PaketController::class);
    Route::post('/logout', [AuthController::class, 'logout']);
});
