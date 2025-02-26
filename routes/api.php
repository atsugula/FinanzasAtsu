<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\TransactionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| Aquí registramos todas las rutas de la API.
| Estas rutas son cargadas por RouteServiceProvider y están bajo el prefijo "api".
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->group(function () {
    Route::post('/auth/login', [AuthController::class, 'login']);
    Route::middleware('api.auth')->post('/auth/logout', [AuthController::class, 'logout']);
    Route::post('/register', [RegisterController::class, 'store'])->middleware('guest')->name('register.perform');
});

Route::middleware('api.auth')->prefix('v1')->group(function () {
    Route::get('/user', function (Request $request) {
        return response()->json(['user' => $request->user()]);
    });
    Route::apiResource('transactions', TransactionController::class);
    Route::get('getData', [HomeController::class, 'getDataSelects']);
});

/* Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
    Route::get('/user', function (Request $request) {
        return response()->json(['user' => $request->user()]);
    });
    Route::apiResource('transactions', TransactionController::class);
    Route::get('getData', [HomeController::class, 'getDataSelects']);
}); */
