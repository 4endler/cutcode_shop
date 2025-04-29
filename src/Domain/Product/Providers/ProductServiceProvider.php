<?php

namespace Domain\Product\Providers;

use Domain\Catalog\Providers\ActionsServiceProvider;
use Illuminate\Support\ServiceProvider;

// use Illuminate\Support\ServiceProvider;


class ProductServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->registerRoutes();
    }

    public function register(): void
    {
        $this->app->register(
            ActionsServiceProvider::class  
        );
    }

    public function registerRoutes(): void
    {
        // $this->loadRoutesFrom(base_path('routes/Auth/web.php'));
    }
}
