<?php

namespace RedJasmine\PointsMall\Application;

use Illuminate\Support\ServiceProvider;
use RedJasmine\PointsMall\Domain\Contracts\ProductServiceInterface;
use RedJasmine\PointsMall\Domain\Repositories\PointsExchangeOrderReadRepositoryInterface;
use RedJasmine\PointsMall\Domain\Repositories\PointsExchangeOrderRepositoryInterface;
use RedJasmine\PointsMall\Domain\Repositories\PointsProductReadRepositoryInterface;
use RedJasmine\PointsMall\Domain\Repositories\PointsProductRepositoryInterface;
use RedJasmine\PointsMall\Infrastructure\ReadRepositories\Mysql\PointsExchangeOrderReadRepository;
use RedJasmine\PointsMall\Infrastructure\ReadRepositories\Mysql\PointsProductReadRepository;
use RedJasmine\PointsMall\Infrastructure\Repositories\Eloquent\PointsExchangeOrderRepository;
use RedJasmine\PointsMall\Infrastructure\Repositories\Eloquent\PointsProductRepository;
use RedJasmine\PointsMall\Infrastructure\Services\ProductServiceIntegration;

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


        $this->app->bind(ProductServiceInterface::class, ProductServiceIntegration::class);
    }

    public function boot() : void
    {
    }
}
