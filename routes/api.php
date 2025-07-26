<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Api\V1\GoalController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\TransactionController;
use App\Http\Controllers\Api\V1\ExpensesCategoryController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| Aquí registramos todas las rutas de la API.
| Estas rutas son cargadas por RouteServiceProvider y están bajo el prefijo "api".
|--------------------------------------------------------------------------
*/

Route::post('/v1/deploy-hook', function (\Illuminate\Http\Request $request) {
    $signature = $request->header('X-Hub-Signature-256');
    $payload = $request->getContent();

    $secret = config('services.github.webhook_secret', 'not_found');
    $hash = 'sha256=' . hash_hmac('sha256', $payload, $secret);

    if (!hash_equals($hash, $signature)) {
        Log::warning('Firma de GitHub inválida.' . $secret);
        abort(403, 'Unauthorized');
    }

    $scriptPath = config('services.github.deploy_script_path', 'not_found');

    if (!file_exists($scriptPath)) {
        Log::error('Script de despliegue no encontrado');
        return response('Script no encontrado', 500);
    }

    exec("$scriptPath 2>&1", $output, $exitCode);

    Log::info('Despliegue ejecutado', [
        'exit_code' => $exitCode,
        'output' => $output,
    ]);

    if ($exitCode !== 0) {
        return response('Error en el despliegue', 500);
    }
	
	return response('Despliegue OK', 200);
});

Route::prefix('v1')->group(function () {
    Route::post('/auth/login', [AuthController::class, 'login']);
    Route::middleware('api.auth')->post('/auth/logout', [AuthController::class, 'logout']);
    Route::post('/register', [AuthController::class, 'register'])->middleware('guest')->name('register.api');
    Route::post('/reset-password', [AuthController::class, 'sendPasswordResetLink'])->middleware('guest')->name('reset.api');
});

Route::middleware('api.auth')->prefix('v1')->group(function () {
    Route::get('/user', function (Request $request) {
        return response()->json(['user' => $request->user()]);
    });
    Route::apiResource('goals', GoalController::class);
    Route::apiResource('transactions', TransactionController::class);
    Route::apiResource('expenses-categories', ExpensesCategoryController::class);
    Route::get('getData', [HomeController::class, 'getDataSelects']);
});
