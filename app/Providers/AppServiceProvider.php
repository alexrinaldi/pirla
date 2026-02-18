<?php

declare(strict_types=1);

namespace App\Providers;

use App\Infrastructure\Tenancy\CurrentHotel;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(CurrentHotel::class, function () {
            return new CurrentHotel();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
