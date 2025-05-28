<?php

namespace RedJasmine\Order\Domain;

use Illuminate\Support\ServiceProvider;
use RedJasmine\Order\Domain\Generator\OrderNoGenerator;
use RedJasmine\Order\Domain\Generator\OrderNoGeneratorInterface;
use RedJasmine\Order\Domain\Generator\OrderProductNoGenerator;
use RedJasmine\Order\Domain\Generator\OrderProductNoGeneratorInterface;
use RedJasmine\Order\Domain\Generator\RefundNoGenerator;
use RedJasmine\Order\Domain\Generator\RefundNoGeneratorInterface;
use RedJasmine\Order\Domain\Models\Order;
use RedJasmine\Order\Domain\Observer\OrderFlowObserver;


/**
 * 订单 领域层 服务提供者
 */
class OrderDomainServiceProvider extends ServiceProvider
{


    public function register() : void
    {

        $this->app->bind(OrderNoGeneratorInterface::class, OrderNoGenerator::class);
        $this->app->bind(RefundNoGeneratorInterface::class, RefundNoGenerator::class);
        $this->app->bind(OrderProductNoGeneratorInterface::class, OrderProductNoGenerator::class);

    }

    public function boot() : void
    {

        Order::observe(OrderFlowObserver::class);

    }
}
