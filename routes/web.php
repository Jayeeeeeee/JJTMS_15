<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\DashboardController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Authentication Routes provided by Laravel Breeze
require __DIR__ . '/auth.php';

// Dashboard Route
//Route::get('/dashboard', function () {
//    return view('dashboard');
//})->middleware(['auth'])->name('dashboard');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware(['auth']);

// User Management Routes (Admin Only)
Route::resource('users', UserController::class)->middleware(['auth', 'role:Admin']);

// Project Routes
Route::resource('projects', ProjectController::class)->middleware(['auth', 'role:Admin,Team Leader']);

// Task Routes
Route::get('tasks/create', [TaskController::class, 'create'])->name('tasks.create')->middleware(['auth', 'role:Admin,Team Leader']);
Route::post('tasks', [TaskController::class, 'store'])->name('tasks.store')->middleware(['auth', 'role:Admin,Team Leader']);
Route::post('tasks/{task}/complete', [TaskController::class, 'complete'])->name('tasks.complete')->middleware(['auth', 'role:Team Member']);
Route::resource('tasks', TaskController::class);

// Task Routes (Admin and Team Leader)
Route::middleware(['auth', 'role:Admin,Team Leader'])->group(function () {
    Route::resource('tasks', TaskController::class);
});

//
Route::get('/login', [AuthenticatedSessionController::class, 'create'])
    ->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store']);
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('logout');

Route::get('/register', [RegisteredUserController::class, 'create'])
    ->name('register');
Route::post('/register', [RegisteredUserController::class, 'store']);

Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])
    ->name('password.request');
Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
    ->name('password.email');
Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])
    ->name('password.reset');
Route::post('/reset-password', [NewPasswordController::class, 'store'])
    ->name('password.update');

Route::get('/email/verify', [EmailVerificationPromptController::class, '__invoke'])
    ->name('verification.notice');
Route::get('/email/verify/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
    ->name('verification.verify')->middleware(['signed']);
Route::post('/email/resend', [EmailVerificationPromptController::class, 'resend'])
    ->name('verification.send');

//Auth::routes();

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
