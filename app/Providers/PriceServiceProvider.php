<?php

namespace Grafit\Providers;

use Illuminate\Support\ServiceProvider;
use Grafit\Services\PriceManager;

class PriceServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Services\PriceManager', function ($app) {
                  return new PriceManager;
        });
    }
}
