<?php

namespace RedJasmine\Payment\Domain;

use Illuminate\Support\ServiceProvider;
use RedJasmine\Payment\Domain\Generator\NotifyNumberGenerator;
use RedJasmine\Payment\Domain\Generator\NotifyNumberGeneratorInterface;
use RedJasmine\Payment\Domain\Generator\RefundNumberGenerator;
use RedJasmine\Payment\Domain\Generator\RefundNumberGeneratorInterface;
use RedJasmine\Payment\Domain\Generator\SettleNumberGenerator;
use RedJasmine\Payment\Domain\Generator\SettleNumberGeneratorInterface;
use RedJasmine\Payment\Domain\Generator\TradeNumberGenerator;
use RedJasmine\Payment\Domain\Generator\TradeNumberGeneratorInterface;
use RedJasmine\Payment\Domain\Generator\TransferNumberGenerator;
use RedJasmine\Payment\Domain\Generator\TransferNumberGeneratorInterface;

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


        $this->app->bind(
            TransferNumberGeneratorInterface::class,
            TransferNumberGenerator::class
        );


        $this->app->bind(
            SettleNumberGeneratorInterface::class,
            SettleNumberGenerator::class
        );


    }


    public function boot() : void
    {

    }
}
