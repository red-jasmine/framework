<?php

namespace RedJasmine\Order\Application;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use RedJasmine\Ecommerce\Domain\Models\Casts\AmountCastTransformer;
use RedJasmine\Ecommerce\Domain\Models\ValueObjects\Amount;
use RedJasmine\Order\Application\Listeners\RefundHandleListener;
use RedJasmine\Order\Domain\Events\OrderShippedEvent;
use RedJasmine\Order\Domain\Events\OrderShippingEvent;
use RedJasmine\Order\Domain\Models\Casts\PromiseServiceValueCastTransformer;
use RedJasmine\Order\Domain\Repositories\OrderLogisticsReadRepositoryInterface;
use RedJasmine\Order\Domain\Repositories\OrderLogisticsRepositoryInterface;
use RedJasmine\Order\Domain\Repositories\OrderPaymentReadRepositoryInterface;
use RedJasmine\Order\Domain\Repositories\OrderPaymentRepositoryInterface;
use RedJasmine\Order\Domain\Repositories\OrderReadRepositoryInterface;
use RedJasmine\Order\Domain\Repositories\OrderRepositoryInterface;
use RedJasmine\Order\Domain\Repositories\RefundReadRepositoryInterface;
use RedJasmine\Order\Domain\Repositories\RefundRepositoryInterface;
use RedJasmine\Order\Infrastructure\ReadRepositories\Mysql\OrderLogisticsReadRepository;
use RedJasmine\Order\Infrastructure\ReadRepositories\Mysql\OrderPaymentReadRepository;
use RedJasmine\Order\Infrastructure\ReadRepositories\Mysql\OrderReadRepository;
use RedJasmine\Order\Infrastructure\ReadRepositories\Mysql\RefundReadRepository;
use RedJasmine\Order\Infrastructure\Repositories\Eloquent\OrderLogisticsRepository;
use RedJasmine\Order\Infrastructure\Repositories\Eloquent\OrderPaymentRepository;
use RedJasmine\Order\Infrastructure\Repositories\Eloquent\OrderRepository;
use RedJasmine\Order\Infrastructure\Repositories\Eloquent\RefundRepository;


/**
 * 订单 应用层 服务提供者
 */
class OrderApplicationServiceProvider extends ServiceProvider
{


    public function register() : void
    {

        $this->app->bind(OrderRepositoryInterface::class, OrderRepository::class);
        $this->app->bind(OrderReadRepositoryInterface::class, OrderReadRepository::class);

        $this->app->bind(RefundRepositoryInterface::class, RefundRepository::class);
        $this->app->bind(RefundReadRepositoryInterface::class, RefundReadRepository::class);


        $this->app->bind(OrderPaymentRepositoryInterface::class, OrderPaymentRepository::class);
        $this->app->bind(OrderPaymentReadRepositoryInterface::class, OrderPaymentReadRepository::class);


        $this->app->bind(OrderLogisticsReadRepositoryInterface::class, OrderLogisticsReadRepository::class);
        $this->app->bind(OrderLogisticsRepositoryInterface::class, OrderLogisticsRepository::class);



        $config = $this->app->make('config');

        $config->set('data.casts.' . Amount::class, AmountCastTransformer::class);
        $config->set('data.transformers.' . Amount::class, AmountCastTransformer::class);



    }

    public function boot() : void
    {
        Event::listen(OrderShippingEvent::class, RefundHandleListener::class);
        Event::listen(OrderShippedEvent::class, RefundHandleListener::class);
    }
}
