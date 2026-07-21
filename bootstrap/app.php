<?php

use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\EventManagerMiddleware;
use App\Http\Middleware\SecurityHeaders;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )

    ->withMiddleware(function (Middleware $middleware) {
        // Exclude Paystack webhook from CSRF verification
        $middleware->validateCsrfTokens(except: [
            'webhook/paystack',
        ]);

        $middleware->append(SecurityHeaders::class);

        $middleware->alias([
            'admin' => AdminMiddleware::class,
            'event_manager' => EventManagerMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->withSchedule(function (Schedule $schedule) {
        $schedule->command('queue:prune-failed --hours=168')
            ->dailyAt('01:00')
            ->withoutOverlapping();

        $schedule->command('orders:cancel-stale-pending --hours=24')
            ->hourly()
            ->withoutOverlapping();

        $schedule->command('users:delete-unverified')
            ->dailyAt('02:00')
            ->withoutOverlapping();
    })
    ->create();
