<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::share('currentSchool', null);
        View::share('schoolRoutePrefix', '');
        View::share('schoolRoute', fn (string $path = '') => '/' . ltrim($path, '/'));
    }
}
