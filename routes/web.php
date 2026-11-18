<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Authentication\LoginController;
use App\Http\Controllers\EmployeeManagementController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserManagementController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [LoginController::class, 'index'])->name('login');
    Route::post('/login', [LoginController::class, 'processLogin'])
        ->middleware('throttle:5,1')
        ->name('process_login');
});

Route::middleware(['auth', 'active'])->group(function (): void {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::view('/charts', 'pages.charts')->name('charts');
    Route::view('/tables', 'pages.tables')->name('tables');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::middleware('admin')->prefix('admin/users')->name('admin.users.')->group(function (): void {
        Route::get('/', [UserManagementController::class, 'index'])->name('index');
        Route::post('/', [UserManagementController::class, 'store'])->name('store');
        Route::put('/{user}', [UserManagementController::class, 'update'])->name('update');
        Route::patch('/{user}/status', [UserManagementController::class, 'updateStatus'])->name('status');
        Route::delete('/{user}', [UserManagementController::class, 'destroy'])->name('destroy');
    });

    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function (): void {
        Route::resource('employees', EmployeeManagementController::class)->except(['show']);
    });
});
