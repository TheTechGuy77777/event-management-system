<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PublicEventController;
use App\Http\Controllers\WaitlistController;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/events/{slug}', [PublicEventController::class, 'show'])->name('events.show');
Route::get('/pricing', function () {
    return view('public.pricing');
})->name('pricing');
Route::get('/about', function () {
    return view('public.about');
})->name('about');
Route::get('/contact', function () {
    return view('public.contact');
})->name('contact');
Route::get('/terms', function () {
    return view('public.terms');
})->name('terms');

Route::get('/privacy', function () {
    return view('public.privacy');
})->name('privacy');

Route::get('/refund-policy', function () {
    return view('public.refund-policy');
})->name('refund-policy');


// Rate limited routes
Route::middleware('throttle:10,1')->group(function () {
    Route::post('/events/{slug}/waitlist', [WaitlistController::class, 'store'])->name('waitlist.store');
});
