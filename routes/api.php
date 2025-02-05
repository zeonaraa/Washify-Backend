<?php

use App\Http\Controllers\{
    AuthController,
    OutletController,
    PaketController,
    UserController,
    MemberController,
    TransaksiController,
    DetailTransaksiController,
    ReportController,
    DashboardController
};
use App\Http\Middleware\{RedirectIfAuthenticatedApi, CheckJwtToken};

Route::get('/', function () {
    return view('welcome');
});


Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api');
Route::get('/me', [AuthController::class, 'me'])->middleware('auth:api');
Route::put('/me', [AuthController::class, 'updateMe'])->middleware('auth:api');
Route::get('/refresh', [AuthController::class, 'refresh'])->middleware('auth:api');


Route::middleware(['auth:api'])->group(function () {
    Route::apiResource('outlets', OutletController::class);
    Route::apiResource('pakets', PaketController::class);
    Route::apiResource('users', UserController::class);
    Route::apiResource('members', MemberController::class);
    Route::apiResource('transaksis', TransaksiController::class);
    Route::apiResource('details', DetailTransaksiController::class);
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/report/members', [ReportController::class, 'generateMemberReport']);
    Route::get('/dashboard/report', [ReportController::class, 'generateReport']);

});
