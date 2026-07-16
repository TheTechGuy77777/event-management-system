<?php

use App\Http\Controllers\CheckoutController;
use Illuminate\Support\Facades\Route;

// Checkout Routes
Route::middleware('throttle:10,1')->group(function () {
    Route::get('/events/{slug}/checkout', [CheckoutController::class, 'index'])->name('checkout');
    Route::post('/events/{slug}/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::post('/promo-codes/validate', [CheckoutController::class, 'validatePromo'])->name('promo.validate');
});

Route::get('/events/{slug}/checkout/callback', [CheckoutController::class, 'callback'])->name('checkout.callback');
Route::get('/events/{slug}/success', [CheckoutController::class, 'success'])->name('checkout.success');
