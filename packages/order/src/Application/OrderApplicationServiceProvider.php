<?php

namespace RedJasmine\Order\Application;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use RedJasmine\Order\Application\Listeners\RefundHandleListener;
use RedJasmine\Order\Domain\Events\OrderShippedEvent;
use RedJasmine\Order\Domain\Events\OrderShippingEvent;
use RedJasmine\Order\Domain\Repositories\OrderCardKeyRepositoryInterface;
use RedJasmine\Order\Domain\Repositories\OrderLogisticsRepositoryInterface;
use RedJasmine\Order\Domain\Repositories\OrderPaymentRepositoryInterface;
use RedJasmine\Order\Domain\Repositories\OrderRepositoryInterface;
use RedJasmine\Order\Domain\Repositories\RefundRepositoryInterface;
use RedJasmine\Order\Infrastructure\Repositories\OrderCardKeyRepository;
use RedJasmine\Order\Infrastructure\Repositories\OrderLogisticsRepository;
use RedJasmine\Order\Infrastructure\Repositories\OrderPaymentRepository;
use RedJasmine\Order\Infrastructure\Repositories\OrderRepository;
use RedJasmine\Order\Infrastructure\Repositories\RefundRepository;

/**
 * 订单应用服务提供者
 *
 * 使用统一的仓库接口，简化服务绑定
 */
class OrderApplicationServiceProvider extends ServiceProvider
{
    public function register() : void
    {
        // 统一仓库接口绑定，支持读写操作
        $this->app->bind(OrderRepositoryInterface::class, OrderRepository::class);
        $this->app->bind(RefundRepositoryInterface::class, RefundRepository::class);
        $this->app->bind(OrderPaymentRepositoryInterface::class, OrderPaymentRepository::class);
        $this->app->bind(OrderLogisticsRepositoryInterface::class, OrderLogisticsRepository::class);
        $this->app->bind(OrderCardKeyRepositoryInterface::class, OrderCardKeyRepository::class);
    }

    public function boot() : void
    {
        Event::listen(OrderShippingEvent::class, RefundHandleListener::class);
        Event::listen(OrderShippedEvent::class, RefundHandleListener::class);
    }
}
