<?php

use Illuminate\Support\Facades\Route;

Route::middleware('web')
    ->group(function () {
        require __DIR__ . '/auth.php';
        require __DIR__ . '/public.php';
        require __DIR__ . '/checkout.php';
        require __DIR__ . '/event-manager.php';
        require __DIR__ . '/admin.php';
    });

require __DIR__ . '/webhook.php';

Route::get('/verify-email-notice', fn() => redirect()->route('login'))->name('verification.notice');
