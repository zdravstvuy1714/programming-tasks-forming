<?php

namespace App\Providers;

use App\Listeners\UpdateCurrentTasks;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        Registered::class => [
            UpdateCurrentTasks::class
        ],
    ];

    public function boot()
    {
        //
    }
}
