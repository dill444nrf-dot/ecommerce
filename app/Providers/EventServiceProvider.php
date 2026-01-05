<?php

namespace App\Providers;

use App\Events\OrderPaidEvent;
use App\Listeners\MergeCartListener;
use App\Listeners\SendOrderPaidEmail;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        Login::class => [
            MergeCartListener::class,
        ],

        OrderPaidEvent::class => [
            SendOrderPaidEmail::class,
        ],
    ];
}
