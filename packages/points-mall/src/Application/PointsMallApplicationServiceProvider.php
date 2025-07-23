<?php

namespace RedJasmine\PointsMall\Application;

use Illuminate\Support\ServiceProvider;
use RedJasmine\PointsMall\Domain\Repositories\PointsExchangeOrderReadRepositoryInterface;
use RedJasmine\PointsMall\Domain\Repositories\PointsExchangeOrderRepositoryInterface;
use RedJasmine\PointsMall\Domain\Repositories\PointsProductReadRepositoryInterface;
use RedJasmine\PointsMall\Domain\Repositories\PointsProductRepositoryInterface;
use RedJasmine\PointsMall\Infrastructure\ReadRepositories\Mysql\PointsExchangeOrderReadRepository;
use RedJasmine\PointsMall\Infrastructure\ReadRepositories\Mysql\PointsProductReadRepository;
use RedJasmine\PointsMall\Infrastructure\Repositories\Eloquent\PointsExchangeOrderRepository;
use RedJasmine\PointsMall\Infrastructure\Repositories\Eloquent\PointsProductRepository;

class PointsMallApplicationServiceProvider extends ServiceProvider
{
    public function register() : void
    {
        // 注册仓库绑定
        $this->app->bind(
            PointsProductRepositoryInterface::class,
            PointsProductRepository::class
        );

        $this->app->bind(
            PointsProductReadRepositoryInterface::class,
            PointsProductReadRepository::class
        );

        $this->app->bind(
            PointsExchangeOrderRepositoryInterface::class,
            PointsExchangeOrderRepository::class
        );

        $this->app->bind(
            PointsExchangeOrderReadRepositoryInterface::class,
            PointsExchangeOrderReadRepository::class
        );

    }

    public function boot() : void
    {
    }
}
