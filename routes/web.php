<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Authentication\LoginController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [LoginController::class, 'index'])->name('login');
    Route::post('/login', [LoginController::class, 'processLogin'])
        ->middleware('throttle:5,1')
        ->name('process_login');
});

Route::middleware('auth')->group(function (): void {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});
