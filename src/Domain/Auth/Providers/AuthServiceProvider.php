<?php

namespace Domain\Auth\Providers;

// use Illuminate\Support\ServiceProvider;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        
    ];

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
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
        $this->loadRoutesFrom(__DIR__.'/../Routes/web.php');
    }
}
