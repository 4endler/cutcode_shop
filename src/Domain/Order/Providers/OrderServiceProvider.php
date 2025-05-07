<?php

namespace Domain\Order\Providers;

use Illuminate\Support\ServiceProvider;

class OrderServiceProvider extends ServiceProvider
{
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
        $this->loadRoutesFrom(base_path('routes/Order/web.php'));
    }
}
