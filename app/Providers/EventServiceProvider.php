<?php

namespace App\Providers;

use App\Events\AfterSessionRegenerated;
use App\Listeners\SendEmailNewUserListener;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }
    protected $listen = [
        Registered::class => [
            SendEmailNewUserListener::class
        ]
    ];
    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
       
        Event::listen(AfterSessionRegenerated::class, function(AfterSessionRegenerated $event){
            cart()->updateStorageId($event->oldSessionId, $event->newSessionId);
        });
    }
}
