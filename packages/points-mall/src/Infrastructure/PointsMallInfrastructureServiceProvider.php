<?php

namespace RedJasmine\PointsMall\Infrastructure;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use RedJasmine\Payment\Domain\Events\Trades\TradePaidEvent;
use RedJasmine\PointsMall\Infrastructure\Services\PaymentServiceIntegration;

class PointsMallInfrastructureServiceProvider extends ServiceProvider
{
    public function register() : void
    {

    }

    public function boot() : void
    {
        Event::listen(TradePaidEvent::class, [PaymentServiceIntegration::class, 'listenTradePaidEvent']);
    }
}
