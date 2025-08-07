<?php

namespace RedJasmine\Shopping\Infrastructure;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use RedJasmine\Payment\Domain\Events\Trades\TradePaidEvent;
use RedJasmine\Shopping\Infrastructure\Services\PaymentServiceIntegration;

class ShoppingInfrastructureServiceProvider extends ServiceProvider
{
    public function register() : void
    {

    }

    public function boot() : void
    {
        Event::listen(TradePaidEvent::class, [
            PaymentServiceIntegration::class, 'listenTradePaidEvent'
        ]);
    }
}
