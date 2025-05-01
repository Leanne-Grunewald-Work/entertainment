<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Laravel\Fortify\Contracts\LoginResponse;
use Laravel\Fortify\Contracts\RegisterResponse;
use App\Actions\Fortify\RedirectToHome;

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
        $this->app->bind(LoginResponse::class, RedirectToHome::class);
        $this->app->bind(RegisterResponse::class, RedirectToHome::class);
    }
}
