<?php

namespace RedJasmine\Order\Domain;

use Illuminate\Support\ServiceProvider;
use RedJasmine\Order\Domain\Models\Order;
use RedJasmine\Order\Domain\Observer\OrderFlowObserver;
use RedJasmine\Order\Domain\Types\OrderTypeManage;


/**
 * 订单 领域层 服务提供者
 */
class OrderDomainServiceProvider extends ServiceProvider
{


    public function register() : void
    {


        $this->app->bind(OrderTypeManage::class, function () {
            return new OrderTypeManage(config('red-jasmine-order.types', []));
        });

    }

    public function boot() : void
    {

        Order::observe(OrderFlowObserver::class);

    }
}
