<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ResetPassword;
use App\Http\Controllers\ChangePassword;

use App\Http\Controllers\Web\AccountController;
use App\Http\Controllers\Web\CategoryController;
use App\Http\Controllers\Web\TransactionController;
use App\Http\Controllers\Web\SettingsController;

/*
|--------------------------------------------------------------------------
| Landing
|--------------------------------------------------------------------------
*/
Route::get('/', fn() => redirect()->route('dashboard'))
    ->middleware('auth');

/*
|--------------------------------------------------------------------------
| Auth (web)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store'])->name('register.perform');

    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.perform');

    Route::get('/reset-password', [ResetPassword::class, 'show'])->name('reset-password');
    Route::post('/reset-password', [ResetPassword::class, 'send'])->name('reset.perform');

    Route::get('/change-password', [ChangePassword::class, 'show'])->name('change-password');
    Route::post('/change-password', [ChangePassword::class, 'update'])->name('change.perform');
});

/*
|--------------------------------------------------------------------------
| App (protected)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // Dashboard (Inicio)
    Route::get('/home', [HomeController::class, 'index'])
        ->name('home');

    Route::get('/dashboard', [HomeController::class, 'index'])
        ->name('dashboard');

    // Movimientos (CRUD) + Import/Export
    Route::resource('transactions', TransactionController::class)
        ->names('transactions');

    Route::get('transactions/import', [TransactionController::class, 'importView'])
        ->name('transactions.import');

    Route::post('transactions/import', [TransactionController::class, 'importStore'])
        ->name('transactions.import.store');

    Route::get('transactions/export', [TransactionController::class, 'export'])
        ->name('transactions.export');

    // Cuentas (CRUD) - en MVP yo archivaría en destroy
    Route::resource('accounts', AccountController::class)
        ->names('accounts');

    // Categorías (CRUD) - opcional, pero lo dejamos
    Route::resource('categories', CategoryController::class)
        ->names('categories');

    // Ajustes (moneda, mes inicial)
    Route::get('/settings', [SettingsController::class, 'edit'])
        ->name('settings.edit');

    Route::put('/settings', [SettingsController::class, 'update'])
        ->name('settings.update');

    // Logout
    Route::post('/logout', [LoginController::class, 'logout'])
        ->name('logout');
});
