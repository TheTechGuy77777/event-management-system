<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->routes(function () {

            Route::middleware('web')
                ->group(function () {

                    require base_path('routes/web.php');
                    require base_path('routes/auth.php');
                    require base_path('routes/public.php');
                    require base_path('routes/event_manager.php');
                    require base_path('routes/admin.php');
                });
        });
    }
}
