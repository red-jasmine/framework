<?php

namespace RedJasmine\Shopping\Application;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use RedJasmine\Payment\Domain\Events\Trades\TradePaidEvent;
use RedJasmine\Shopping\Application\Listeners\PaymentTradeListener;

class ShoppingApplicationServiceProvider extends ServiceProvider
{


    public function boot() : void
    {
        Event::listen(TradePaidEvent::class, PaymentTradeListener::class);
    }

}