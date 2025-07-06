<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\POSController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('pos')->group(function () {
    Route::post('/login', [POSController::class, 'login']);
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/generate-card', [POSController::class, 'generateCard']);
        Route::post('/recharge-card', [POSController::class, 'rechargeCard']);
        Route::get('/my-sales', [POSController::class, 'salesReport']);
    });
});
