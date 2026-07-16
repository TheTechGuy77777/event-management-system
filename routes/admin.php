<?php

use App\Http\Controllers\Admin\AnnouncementController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CommissionController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\ManagerController;
use App\Http\Controllers\Admin\TransactionController;
use Illuminate\Support\Facades\Route;

// Admin Routes
Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/managers', [ManagerController::class, 'index'])->name('managers');
    Route::patch('/managers/{user}/suspend', [ManagerController::class, 'suspend'])->name('managers.suspend');
    Route::patch('/managers/{user}/ban', [ManagerController::class, 'ban'])->name('managers.ban');
    Route::patch('/managers/{user}/reactivate', [ManagerController::class, 'reactivate'])->name('managers.reactivate');
    Route::get('/events', [EventController::class, 'index'])->name('events');
    Route::middleware('throttle:30,1')->group(function () {
        Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions');
    });
    Route::get('/commission', [CommissionController::class, 'index'])->name('commission');
    Route::post('/commission', [CommissionController::class, 'update'])->name('commission.update');
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::patch('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
    Route::get('/announcements', [AnnouncementController::class, 'index'])->name('announcements');
    Route::post('/announcements', [AnnouncementController::class, 'store'])->name('announcements.store');
});
