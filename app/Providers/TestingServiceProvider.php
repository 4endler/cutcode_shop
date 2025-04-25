<?php

namespace App\Providers;

use Faker\Factory;
use Illuminate\Support\ServiceProvider;
use Support\Testing\FakerImageProvider;

class TestingServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(
            \Faker\Generator::class,
            function () {
                $faker = Factory::create();
                $faker->addProvider(new FakerImageProvider($faker));

                return $faker;
            });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
