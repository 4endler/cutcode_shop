<?php

namespace Domain\Cart\Providers;

use Domain\Cart\CartManager;
use Domain\Cart\Contracts\CartIdentityStorageContract;
use Domain\Cart\StorageIdentities\SessionIdentityStorage;
use Illuminate\Contracts\Session\Session;
use Illuminate\Support\ServiceProvider;

class CartServiceProvider extends ServiceProvider
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

        
        //Вместо этого используем бинд
        // $this->app->singleton(CartManager::class, function() {
        //     return new CartManager(new SessionIdentityStorage());
        // });
        $this->app->bind(CartIdentityStorageContract::class, SessionIdentityStorage::class);
        $this->app->singleton(CartManager::class);

    }
    public function registerRoutes(): void
    {
        $this->loadRoutesFrom(base_path('routes/Cart/web.php'));
    }
}
