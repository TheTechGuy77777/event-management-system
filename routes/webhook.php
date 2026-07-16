<?php

use App\Http\Controllers\WebhookController;
use Illuminate\Support\Facades\Route;

// Webhook Routes
Route::post('/webhook/paystack', [WebhookController::class, 'paystack'])
    ->name('webhook.paystack');
