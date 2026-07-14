<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\EventManager\DashboardController as EventManagerDashboardController;

// Public Routes
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/events/{slug}', [App\Http\Controllers\PublicEventController::class, 'show'])->name('events.show');
Route::get('/events/{slug}/checkout', [App\Http\Controllers\CheckoutController::class, 'index'])->name('checkout');
Route::post('/events/{slug}/checkout', [App\Http\Controllers\CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/events/{slug}/success', [App\Http\Controllers\CheckoutController::class, 'success'])->name('checkout.success');
Route::get('/events/{slug}/checkout/callback', [App\Http\Controllers\CheckoutController::class, 'callback'])->name('checkout.callback');
Route::post('/promo-codes/validate', [App\Http\Controllers\CheckoutController::class, 'validatePromo'])->name('promo.validate');
Route::post('/events/{slug}/waitlist', [App\Http\Controllers\WaitlistController::class, 'store'])->name('waitlist.store');
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
Route::post('/webhook/paystack', [App\Http\Controllers\WebhookController::class, 'paystack'])
    ->name('webhook.paystack');


// Rate limited routes
Route::middleware('throttle:10,1')->group(function () {
    Route::post('/events/{slug}/checkout', [App\Http\Controllers\CheckoutController::class, 'store'])->name('checkout.store');
    Route::post('/events/{slug}/waitlist', [App\Http\Controllers\WaitlistController::class, 'store'])->name('waitlist.store');
    Route::post('/promo-codes/validate', [App\Http\Controllers\CheckoutController::class, 'validatePromo'])->name('promo.validate');
});


// Event Manager Routes
Route::middleware(['auth', 'verified', 'event_manager'])->prefix('dashboard')->name('dashboard.')->group(function () {
    Route::get('/', [EventManagerDashboardController::class, 'index'])->name('index');

    // Events
    Route::get('/events', [App\Http\Controllers\EventManager\EventController::class, 'index'])->name('events.index');
    Route::get('/events/create', [App\Http\Controllers\EventManager\EventController::class, 'create'])->name('events.create');
    Route::get('/events/{event}/edit', [App\Http\Controllers\EventManager\EventController::class, 'edit'])->name('events.edit');
    Route::patch('/events/{event}', [App\Http\Controllers\EventManager\EventController::class, 'update'])->name('events.update');

    Route::get('/promo-codes', [App\Http\Controllers\EventManager\PromoCodeController::class, 'index'])->name('promo-codes.index');
    Route::post('/promo-codes', [App\Http\Controllers\EventManager\PromoCodeController::class, 'store'])->name('promo-codes.store');
    Route::delete('/promo-codes/{promoCode}', [App\Http\Controllers\EventManager\PromoCodeController::class, 'destroy'])->name('promo-codes.destroy');
    Route::patch('/promo-codes/{promoCode}/toggle', [App\Http\Controllers\EventManager\PromoCodeController::class, 'toggle'])->name('promo-codes.toggle');

    Route::post('/events', [App\Http\Controllers\EventManager\EventController::class, 'store'])->name('events.store');
    Route::patch('/events/{event}/publish', [App\Http\Controllers\EventManager\EventController::class, 'publish'])->name('events.publish');
    Route::delete('/events/{event}', [App\Http\Controllers\EventManager\EventController::class, 'destroy'])->name('events.destroy');

    Route::get('/events/{event}/waitlist', [App\Http\Controllers\EventManager\WaitlistManagerController::class, 'index'])->name('events.waitlist');
    Route::post('/events/{event}/waitlist/{waitlist}/notify', [App\Http\Controllers\EventManager\WaitlistManagerController::class, 'notify'])->name('events.waitlist.notify');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Account
    Route::get('/account', [App\Http\Controllers\EventManager\BankAccountController::class, 'index'])->name('account');
    Route::post('/account', [App\Http\Controllers\EventManager\BankAccountController::class, 'update'])->name('account.update');

    // attendees 
    Route::get('/events/{event}/attendees', [App\Http\Controllers\EventManager\AttendeeController::class, 'index'])->name('events.attendees');
    Route::get('/events/{event}/attendees/export', [App\Http\Controllers\EventManager\AttendeeController::class, 'export'])->name('events.attendees.export');

    //check in
    Route::get('/events/{event}/checkin', [App\Http\Controllers\EventManager\CheckInController::class, 'index'])->name('events.checkin');
    Route::post('/events/{event}/checkin', [App\Http\Controllers\EventManager\CheckInController::class, 'scan'])->name('events.checkin.scan');


    // Notifications
    Route::get('/notifications', [App\Http\Controllers\EventManager\NotificationController::class, 'index'])->name('notifications');
    Route::get('/notifications/{notification}', [App\Http\Controllers\EventManager\NotificationController::class, 'show'])->name('notifications.show');
    Route::get('/notifications/{notification}/read', [App\Http\Controllers\EventManager\NotificationController::class, 'markRead'])->name('notifications.read');
    Route::patch('/notifications/read-all', [App\Http\Controllers\EventManager\NotificationController::class, 'markAllRead'])->name('notifications.read-all');
});


// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/managers', [App\Http\Controllers\Admin\ManagerController::class, 'index'])->name('managers');
    Route::patch('/managers/{user}/suspend', [App\Http\Controllers\Admin\ManagerController::class, 'suspend'])->name('managers.suspend');
    Route::patch('/managers/{user}/ban', [App\Http\Controllers\Admin\ManagerController::class, 'ban'])->name('managers.ban');
    Route::patch('/managers/{user}/reactivate', [App\Http\Controllers\Admin\ManagerController::class, 'reactivate'])->name('managers.reactivate');
    Route::get('/events', [App\Http\Controllers\Admin\EventController::class, 'index'])->name('events');
    Route::get('/transactions', [App\Http\Controllers\Admin\TransactionController::class, 'index'])->name('transactions');
    Route::get('/commission', [App\Http\Controllers\Admin\CommissionController::class, 'index'])->name('commission');
    Route::post('/commission', [App\Http\Controllers\Admin\CommissionController::class, 'update'])->name('commission.update');
    Route::get('/categories', [App\Http\Controllers\Admin\CategoryController::class, 'index'])->name('categories');
    Route::post('/categories', [App\Http\Controllers\Admin\CategoryController::class, 'store'])->name('categories.store');
    Route::patch('/categories/{category}', [App\Http\Controllers\Admin\CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [App\Http\Controllers\Admin\CategoryController::class, 'destroy'])->name('categories.destroy');
    Route::get('/announcements', [App\Http\Controllers\Admin\AnnouncementController::class, 'index'])->name('announcements');
    Route::post('/announcements', [App\Http\Controllers\Admin\AnnouncementController::class, 'store'])->name('announcements.store');
});

require __DIR__ . '/auth.php';
