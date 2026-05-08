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