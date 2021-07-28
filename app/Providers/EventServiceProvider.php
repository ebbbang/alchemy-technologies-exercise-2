<?php

namespace App\Providers;

use Artisan;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Console\Events\CommandFinished;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        if (env('APP_ENV') == 'local') {
            Event::listen(CommandFinished::class, function (CommandFinished $event) {
                if ($event->command == 'migrate:fresh') {
                    Artisan::call('docs');
                }
            });
        }
    }
}
