<?php

namespace RedJasmine\Order\Application;

use Illuminate\Support\ServiceProvider;
use RedJasmine\Order\Domain\Models\Casts\PromiseServiceValueCastTransformer;
use RedJasmine\Order\Domain\Models\ValueObjects\PromiseServiceValue;
use RedJasmine\Order\Domain\Repositories\OrderRepositoryInterface;
use RedJasmine\Order\Domain\Repositories\RefundRepositoryInterface;
use RedJasmine\Order\Infrastructure\ReadRepositories\Mysql\OrderReadRepository;
use RedJasmine\Order\Infrastructure\ReadRepositories\Mysql\RefundReadRepository;
use RedJasmine\Order\Infrastructure\ReadRepositories\OrderReadRepositoryInterface;
use RedJasmine\Order\Infrastructure\ReadRepositories\RefundReadRepositoryInterface;
use RedJasmine\Order\Infrastructure\Repositories\Eloquent\OrderRepository;
use RedJasmine\Order\Infrastructure\Repositories\Eloquent\RefundRepository;
use RedJasmine\Support\Domain\Models\Casts\AmountCastTransformer;
use RedJasmine\Support\Domain\Models\ValueObjects\Amount;


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


        $config = $this->app->make('config');

        $config->set('data.casts.' . Amount::class, AmountCastTransformer::class);
        $config->set('data.transformers.' . Amount::class, AmountCastTransformer::class);


        $config->set('data.casts.' . PromiseServiceValue::class, PromiseServiceValueCastTransformer::class);
        $config->set('data.transformers.' . PromiseServiceValue::class, PromiseServiceValueCastTransformer::class);

       
    }

    public function boot() : void
    {
    }
}
