<?php

namespace RedJasmine\Shopping\Application;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use RedJasmine\Payment\Domain\Events\Trades\TradePaidEvent;
use RedJasmine\Shopping\Application\Listeners\PaymentTradeListener;
use RedJasmine\Shopping\Domain\Contracts\ProductServiceInterface;
use RedJasmine\Shopping\Domain\Contracts\StockServiceInterface;
use RedJasmine\Shopping\Infrastructure\Services\ProductServiceIntegration;
use RedJasmine\Shopping\Infrastructure\Services\StockServiceIntegration;

class ShoppingApplicationServiceProvider extends ServiceProvider
{


    public function register() : void
    {
        // 购物车商品服务接口 和 商品领域服务接口 集成
        $this->app->bind(ProductServiceInterface::class, ProductServiceIntegration::class);
        $this->app->bind(StockServiceInterface::class, StockServiceIntegration::class);

    }

    public function boot() : void
    {
        Event::listen(TradePaidEvent::class, PaymentTradeListener::class);
    }

}