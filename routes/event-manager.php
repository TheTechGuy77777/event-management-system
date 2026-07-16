<?php

use App\Http\Controllers\EventManager\AttendeeController;
use App\Http\Controllers\EventManager\BankAccountController;
use App\Http\Controllers\EventManager\CheckInController;
use App\Http\Controllers\EventManager\DashboardController as EventManagerDashboardController;
use App\Http\Controllers\EventManager\EventController;
use App\Http\Controllers\EventManager\NotificationController;
use App\Http\Controllers\EventManager\PromoCodeController;
use App\Http\Controllers\EventManager\WaitlistManagerController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Event Manager Routes
Route::middleware(['auth', 'verified', 'event_manager'])->prefix('dashboard')->name('dashboard.')->group(function () {
    Route::get('/', [EventManagerDashboardController::class, 'index'])->name('index');

    // Events
    Route::get('/events', [EventController::class, 'index'])->name('events.index');
    Route::get('/events/create', [EventController::class, 'create'])->name('events.create');
    Route::get('/events/{event}/edit', [EventController::class, 'edit'])->name('events.edit');
    Route::post('/events', [EventController::class, 'store'])->name('events.store');
    Route::patch('/events/{event}', [EventController::class, 'update'])->name('events.update');
    Route::patch('/events/{event}/publish', [EventController::class, 'publish'])->name('events.publish');
    Route::delete('/events/{event}', [EventController::class, 'destroy'])->name('events.destroy');

    Route::get('/promo-codes', [PromoCodeController::class, 'index'])->name('promo-codes.index');
    Route::post('/promo-codes', [PromoCodeController::class, 'store'])->name('promo-codes.store');
    Route::delete('/promo-codes/{promoCode}', [PromoCodeController::class, 'destroy'])->name('promo-codes.destroy');
    Route::patch('/promo-codes/{promoCode}/toggle', [PromoCodeController::class, 'toggle'])->name('promo-codes.toggle');

    Route::get('/events/{event}/waitlist', [WaitlistManagerController::class, 'index'])->name('events.waitlist');
    Route::post('/events/{event}/waitlist/{waitlist}/notify', [WaitlistManagerController::class, 'notify'])->name('events.waitlist.notify');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Account
    Route::get('/account', [BankAccountController::class, 'index'])->name('account');
    Route::post('/account', [BankAccountController::class, 'update'])->name('account.update');

    // attendees
    Route::middleware('throttle:10,1')->group(function () {
        Route::get('/events/{event}/attendees', [AttendeeController::class, 'index'])->name('events.attendees');
        Route::get('/events/{event}/attendees/export', [AttendeeController::class, 'export'])->name('events.attendees.export');
    });

    // check in
    Route::get('/events/{event}/checkin', [CheckInController::class, 'index'])->name('events.checkin');
    Route::post('/events/{event}/checkin', [CheckInController::class, 'scan'])->name('events.checkin.scan');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications');
    Route::get('/notifications/{notification}', [NotificationController::class, 'show'])->name('notifications.show');
    Route::get('/notifications/{notification}/read', [NotificationController::class, 'markRead'])->name('notifications.read');
    Route::patch('/notifications/read-all', [NotificationController::class, 'markAllRead'])->name('notifications.read-all');
});
