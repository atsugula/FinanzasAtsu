<?php

use App\Http\Controllers\V1\ExpensesCategoryController;
use App\Http\Controllers\V1\PaymentExpenseController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\V1\ExpenseController;
use App\Http\Controllers\V1\SavingController;
use App\Http\Controllers\V1\IncomeController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\V1\UserController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ChangePassword;
use App\Http\Controllers\ResetPassword;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/', function () {return redirect('/dashboard');})->middleware('auth');
Route::get('/register', [RegisterController::class, 'create'])->middleware('guest')->name('register');
Route::post('/register', [RegisterController::class, 'store'])->middleware('guest')->name('register.perform');
Route::get('/login', [LoginController::class, 'show'])->middleware('guest')->name('login');
Route::post('/login', [LoginController::class, 'login'])->middleware('guest')->name('login.perform');
Route::get('/reset-password', [ResetPassword::class, 'show'])->middleware('guest')->name('reset-password');
Route::post('/reset-password', [ResetPassword::class, 'send'])->middleware('guest')->name('reset.perform');
Route::get('/change-password', [ChangePassword::class, 'show'])->middleware('guest')->name('change-password');
Route::post('/change-password', [ChangePassword::class, 'update'])->middleware('guest')->name('change.perform');
Route::get('/dashboard', [HomeController::class, 'index'])->name('home')->middleware('auth');

/* Si no esta logeado, no pasa */
Route::group(['middleware' => 'auth'], function () {
	/* Modulos software */
	Route::resource('users', UserController::class)->names('users');
	Route::resource('incomes', IncomeController::class)->names('incomes');
	Route::resource('expenses-categories', ExpensesCategoryController::class)->names('expenses-categories');
	Route::resource('expenses', ExpenseController::class)->names('expenses');
	Route::resource('payment-expenses', PaymentExpenseController::class)
		->only('index', 'edit', 'destroy', 'show')->names('payment-expenses');
	Route::resource('savings', SavingController::class)->names('savings');
	/* Paginas de la plantilla */
	Route::get('/virtual-reality', [PageController::class, 'vr'])->name('virtual-reality');
	Route::get('/rtl', [PageController::class, 'rtl'])->name('rtl');
	Route::get('/profile', [UserProfileController::class, 'show'])->name('profile');
	Route::post('/profile', [UserProfileController::class, 'update'])->name('profile.update');
	Route::get('/profile-static', [PageController::class, 'profile'])->name('profile-static'); 
	Route::get('/sign-in-static', [PageController::class, 'signin'])->name('sign-in-static');
	Route::get('/sign-up-static', [PageController::class, 'signup'])->name('sign-up-static'); 
	Route::get('/{page}', [PageController::class, 'index'])->name('page');
	Route::post('logout', [LoginController::class, 'logout'])->name('logout');
});
