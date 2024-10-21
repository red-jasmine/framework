<?php

namespace RedJasmine\Order\Domain;

use Illuminate\Support\ServiceProvider;
use RedJasmine\Order\Domain\Models\Order;
use RedJasmine\Order\Domain\Observer\OrderFlowObserver;


/**
 * 订单 领域层 服务提供者
 */
class OrderDomainServiceProvider extends ServiceProvider
{


    public function register() : void
    {


    }

    public function boot() : void
    {

        Order::observe(OrderFlowObserver::class);
    }
}
