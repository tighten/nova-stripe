<?php

namespace Tighten\NovaStripe;

use Illuminate\Support\ServiceProvider;

class ToolServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app->booted(function (): void {
            $this->routes();
        });
    }

    public function register(): void
    {
        //
    }

    protected function routes(): void
    {
        //
    }
}
