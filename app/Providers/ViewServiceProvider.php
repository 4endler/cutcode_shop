<?php

namespace App\Providers;

use App\View\Composers\NavigaionComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Vite::macro('image', function ($asset) {
            return Vite::asset("resources/images/$asset");
        });

        View::composer('shared.header', NavigaionComposer::class);
    }
}
