<?php

namespace App\Providers;

use App\Providers\Socialite\QuaverSocialiteProvider;
use Illuminate\Support\ServiceProvider;
use Laravel\Socialite\Contracts\Factory;

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
        $socialite = $this->app->make(Factory::class);

        $socialite->extend('quaver', function () use ($socialite) {
            $config = config('services.quaver');

            return $socialite->buildProvider(QuaverSocialiteProvider::class, $config);
        });
    }
}
