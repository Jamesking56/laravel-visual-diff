<?php

namespace Jamesking56\LaravelVisualDiff;

use Illuminate\Support\ServiceProvider;
use Laravel\Dusk\Browser;

class LaravelVisualDiffServiceProvider extends ServiceProvider
{
    /**
     * Register the application services.
     */
    public function register()
    {
        Browser::macro('visualDiff', function (?string $name = null) {
            return app(LaravelVisualDiff::class)
                ->visualDiff($name);
        });
    }
}
