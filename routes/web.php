<?php

use App\Http\Controllers\V1\TransactionController;
use App\Http\Controllers\V1\CategoryController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\V1\UserController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ChangePassword;
use App\Http\Controllers\ResetPassword;
use Illuminate\Support\Facades\Route;

// Landing o redirección
Route::get('/', fn() => redirect('/dashboard'))->middleware('auth');

// Auth
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

// Dashboard
Route::get('/dashboard', [HomeController::class, 'index'])->name('home')->middleware('auth');

// Rutas protegidas
Route::middleware('auth')->group(function () {

    // CRUD principales
    Route::resource('users', UserController::class);
    Route::resource('transactions', TransactionController::class);
	Route::resource('categories', CategoryController::class)->names('categories');

    // Transacciones especiales
    Route::get('transactions/import', [TransactionController::class, 'viewImport'])->name('transactions.import');
    Route::post('transactions/import', [TransactionController::class, 'import'])->name('transactions.import.submit');
    Route::get('transactions/export', [TransactionController::class, 'export'])->name('transactions.export');

    // Perfil
    Route::get('/profile', [UserProfileController::class, 'show'])->name('profile');
    Route::post('/profile', [UserProfileController::class, 'update'])->name('profile.update');

    // Páginas estáticas
    Route::get('/virtual-reality', [PageController::class, 'vr'])->name('virtual-reality');
    Route::get('/rtl', [PageController::class, 'rtl'])->name('rtl');
    Route::get('/profile-static', [PageController::class, 'profile'])->name('profile-static'); 
    Route::get('/sign-in-static', [PageController::class, 'signin'])->name('sign-in-static');
    Route::get('/sign-up-static', [PageController::class, 'signup'])->name('sign-up-static'); 

    // Logout
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');

    // Catch-all de páginas, con restricción para no chocar con otras rutas
    Route::get('/{page}', [PageController::class, 'index'])
        ->where('page', '^[a-z0-9\-]+$')
        ->name('page');
});
