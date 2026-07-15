<?php

use App\Http\Controllers\Admin\AnnouncementController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CommissionController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ManagerController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\EventManager\AttendeeController;
use App\Http\Controllers\EventManager\BankAccountController;
use App\Http\Controllers\EventManager\CheckInController;
use App\Http\Controllers\EventManager\DashboardController as EventManagerDashboardController;
use App\Http\Controllers\EventManager\EventController;
use App\Http\Controllers\EventManager\NotificationController;
use App\Http\Controllers\EventManager\PromoCodeController;
use App\Http\Controllers\EventManager\WaitlistManagerController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicEventController;
use App\Http\Controllers\WaitlistController;
use App\Http\Controllers\WebhookController;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/events/{slug}', [PublicEventController::class, 'show'])->name('events.show');
Route::get('/events/{slug}/checkout', [CheckoutController::class, 'index'])->name('checkout');
Route::post('/events/{slug}/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/events/{slug}/success', [CheckoutController::class, 'success'])->name('checkout.success');
Route::get('/events/{slug}/checkout/callback', [CheckoutController::class, 'callback'])->name('checkout.callback');
Route::post('/promo-codes/validate', [CheckoutController::class, 'validatePromo'])->name('promo.validate');
Route::post('/events/{slug}/waitlist', [WaitlistController::class, 'store'])->name('waitlist.store');
Route::get('/pricing', function () {
    return view('public.pricing');
})->name('pricing');
Route::get('/about', function () {
    return view('public.about');
})->name('about');
Route::get('/contact', function () {
    return view('public.contact');
})->name('contact');

// Paystack Webhook
Route::post('/webhook/paystack', [WebhookController::class, 'paystack'])
    ->name('webhook.paystack');

// Rate limited routes
Route::middleware('throttle:10,1')->group(function () {
    Route::post('/events/{slug}/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::post('/events/{slug}/waitlist', [WaitlistController::class, 'store'])->name('waitlist.store');
    Route::post('/promo-codes/validate', [CheckoutController::class, 'validatePromo'])->name('promo.validate');
});

// Event Manager Routes
Route::middleware(['auth', 'verified', 'event_manager'])->prefix('dashboard')->name('dashboard.')->group(function () {
    Route::get('/', [EventManagerDashboardController::class, 'index'])->name('index');

    // Events
    Route::get('/events', [EventController::class, 'index'])->name('events.index');
    Route::get('/events/create', [EventController::class, 'create'])->name('events.create');
    Route::get('/events/{event}/edit', [EventController::class, 'edit'])->name('events.edit');
    Route::patch('/events/{event}', [EventController::class, 'update'])->name('events.update');

    Route::get('/promo-codes', [PromoCodeController::class, 'index'])->name('promo-codes.index');
    Route::post('/promo-codes', [PromoCodeController::class, 'store'])->name('promo-codes.store');
    Route::delete('/promo-codes/{promoCode}', [PromoCodeController::class, 'destroy'])->name('promo-codes.destroy');
    Route::patch('/promo-codes/{promoCode}/toggle', [PromoCodeController::class, 'toggle'])->name('promo-codes.toggle');

    Route::post('/events', [EventController::class, 'store'])->name('events.store');
    Route::patch('/events/{event}/publish', [EventController::class, 'publish'])->name('events.publish');
    Route::delete('/events/{event}', [EventController::class, 'destroy'])->name('events.destroy');

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
    Route::get('/events/{event}/attendees', [AttendeeController::class, 'index'])->name('events.attendees');
    Route::get('/events/{event}/attendees/export', [AttendeeController::class, 'export'])->name('events.attendees.export');

    // check in
    Route::get('/events/{event}/checkin', [CheckInController::class, 'index'])->name('events.checkin');
    Route::post('/events/{event}/checkin', [CheckInController::class, 'scan'])->name('events.checkin.scan');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications');
    Route::get('/notifications/{notification}', [NotificationController::class, 'show'])->name('notifications.show');
    Route::get('/notifications/{notification}/read', [NotificationController::class, 'markRead'])->name('notifications.read');
    Route::patch('/notifications/read-all', [NotificationController::class, 'markAllRead'])->name('notifications.read-all');
});

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/managers', [ManagerController::class, 'index'])->name('managers');
    Route::patch('/managers/{user}/suspend', [ManagerController::class, 'suspend'])->name('managers.suspend');
    Route::patch('/managers/{user}/ban', [ManagerController::class, 'ban'])->name('managers.ban');
    Route::patch('/managers/{user}/reactivate', [ManagerController::class, 'reactivate'])->name('managers.reactivate');
    Route::get('/events', [App\Http\Controllers\Admin\EventController::class, 'index'])->name('events');
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions');
    Route::get('/commission', [CommissionController::class, 'index'])->name('commission');
    Route::post('/commission', [CommissionController::class, 'update'])->name('commission.update');
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::patch('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
    Route::get('/announcements', [AnnouncementController::class, 'index'])->name('announcements');
    Route::post('/announcements', [AnnouncementController::class, 'store'])->name('announcements.store');
});

require __DIR__.'/auth.php';
