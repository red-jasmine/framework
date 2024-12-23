<?php

namespace RedJasmine\Payment\Domain;

use Illuminate\Support\ServiceProvider;
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

    }

    public function boot() : void
    {
    }
}
