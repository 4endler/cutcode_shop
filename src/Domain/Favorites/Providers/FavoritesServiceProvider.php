<?php

namespace Domain\Favorites\Providers;

use Domain\Favorites\Contracts\FavoriteIdentityStorageContract;
use Domain\Favorites\FavoriteManager;
use Domain\Favorites\StorageIdentities\SessionIdentityStorage;
use Illuminate\Support\ServiceProvider;

class FavoritesServiceProvider extends ServiceProvider
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
        $this->app->bind(FavoriteIdentityStorageContract::class, SessionIdentityStorage::class);
        $this->app->singleton(FavoriteManager::class);

    }
    public function registerRoutes(): void
    {
        $this->loadRoutesFrom(base_path('routes/Favorites/web.php'));
    }
}
