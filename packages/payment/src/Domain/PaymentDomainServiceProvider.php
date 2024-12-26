<?php

namespace RedJasmine\Payment\Domain;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use RedJasmine\Payment\Application\Listeners\RefundChannelListener;
use RedJasmine\Payment\Domain\Events\Refunds\RefundCreatedEvent;
use RedJasmine\Payment\Domain\Generator\NotifyNumberGenerator;
use RedJasmine\Payment\Domain\Generator\NotifyNumberGeneratorInterface;
use RedJasmine\Payment\Domain\Generator\RefundNumberGenerator;
use RedJasmine\Payment\Domain\Generator\RefundNumberGeneratorInterface;
use RedJasmine\Payment\Domain\Generator\TradeNumberGenerator;
use RedJasmine\Payment\Domain\Generator\TradeNumberGeneratorInterface;

class PaymentDomainServiceProvider extends ServiceProvider
{
    public function register() : void
    {


        $this->app->bind(
            TradeNumberGeneratorInterface::class,
            TradeNumberGenerator::class
        );


        $this->app->bind(
            RefundNumberGeneratorInterface::class,
            RefundNumberGenerator::class
        );

        $this->app->bind(
            NotifyNumberGeneratorInterface::class,
            NotifyNumberGenerator::class
        );


    }


    public function boot() : void
    {

    }
}
