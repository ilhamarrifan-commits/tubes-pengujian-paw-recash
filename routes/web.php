<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController; // Will create next
use App\Http\Controllers\ProductController;   // Will create next
use App\Http\Controllers\PosController;       // Will create next
use App\Http\Controllers\AdminReportController;
use App\Http\Controllers\Admin\AdminStaffController;


Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::get('/register', [\App\Http\Controllers\RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [\App\Http\Controllers\RegisterController::class, 'register']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('staff', AdminStaffController::class);
    Route::get('/sales', [AdminReportController::class, 'sales'])->name('sales');
    Route::get('/history', [AdminReportController::class, 'history'])->name('history');
    Route::post('/history/bulk-delete', [AdminReportController::class, 'bulkDelete'])->name('history.bulkDelete');
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'show'])->name('profile');
});


// Manager Routes
Route::middleware(['auth', 'role:manager'])->prefix('manager')->name('manager.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/sales', [AdminReportController::class, 'sales'])->name('sales');
    Route::get('/history', [AdminReportController::class, 'history'])->name('history');
    Route::resource('products', ProductController::class);
    Route::get('/staff', [\App\Http\Controllers\Manager\ManagerStaffController::class, 'index'])->name('staff.index');
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'show'])->name('profile');
});

// Cashier Routes
Route::middleware(['auth', 'role:cashier'])->prefix('cashier')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\PosController::class, 'index'])->name('cashier.dashboard');
    // API for POS logic
    Route::post('/order', [App\Http\Controllers\PosController::class, 'storeOrder'])->name('cashier.order.store');
    Route::get('/history', [App\Http\Controllers\PosController::class, 'history'])->name('cashier.history');
    Route::get('/profile', [App\Http\Controllers\PosController::class, 'profile'])->name('cashier.profile');
});


// Common Profile Action
Route::delete('/profile', [App\Http\Controllers\ProfileController::class, 'destroy'])->middleware('auth')->name('profile.destroy');
