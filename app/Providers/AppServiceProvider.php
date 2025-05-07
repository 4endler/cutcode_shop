<?php

namespace App\Providers;

use Domain\Catalog\Models\Brand;
use Domain\Catalog\Observers\BrandObserver;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Database\Connection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::shouldBeStrict(!app()->isProduction());
        Brand::observe(BrandObserver::class);
        // TODO
        // if(app()->isProduction()) {
        if (1) {
            //Долгий запрос
            DB::listen(function ($query) {
                if ($query->time > 1) {
                    logger()
                        ->channel('telegram')
                        ->debug('query longer than 100ms' . $query->sql, $query->bindings);
                }
            });
        }

        RateLimiter::for('global', function (Request $request) {
            return Limit::perMinute(100)
                ->by($request->user()?->id ?: $request->ip())
                ->response(function (Request $request, array $headers) {
                    return response()->json([
                        'message' => 'Превышен лимит запросов'
                    ], 429, $headers);
                });
        });

        RateLimiter::for('auth', function (Request $request) {
            return Limit::perMinute(5)
                ->by($request->ip())
                ->response(function (Request $request, array $headers) {
                    return response()->json([
                        'message' => 'Слишком много попыток входа. Попробуйте через 1 минуту.'
                    ], 429, $headers);
                });
        });

        Password::defaults(function () {
            return Password::min(8)
                // ->mixedCase()
                // ->numbers()
                // ->symbols()
                // ->uncompromised()
                ;
        });
    }
}
