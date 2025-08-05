<?php

namespace RedJasmine\PointsMall\Application;

use Illuminate\Support\ServiceProvider;
use RedJasmine\PointsMall\Domain\Contracts\OrderServiceInterface;
use RedJasmine\PointsMall\Domain\Contracts\PaymentServiceInterface;
use RedJasmine\PointsMall\Domain\Contracts\ProductServiceInterface;
use RedJasmine\PointsMall\Domain\Contracts\WalletServiceInterface;
use RedJasmine\PointsMall\Domain\Repositories\PointProductCategoryReadRepositoryInterface;
use RedJasmine\PointsMall\Domain\Repositories\PointProductCategoryRepositoryInterface;
use RedJasmine\PointsMall\Domain\Repositories\PointsExchangeOrderReadRepositoryInterface;
use RedJasmine\PointsMall\Domain\Repositories\PointsExchangeOrderRepositoryInterface;
use RedJasmine\PointsMall\Domain\Repositories\PointsProductReadRepositoryInterface;
use RedJasmine\PointsMall\Domain\Repositories\PointsProductRepositoryInterface;
use RedJasmine\PointsMall\Infrastructure\ReadRepositories\Mysql\PointProductCategoryReadRepository;
use RedJasmine\PointsMall\Infrastructure\ReadRepositories\Mysql\PointsExchangeOrderReadRepository;
use RedJasmine\PointsMall\Infrastructure\ReadRepositories\Mysql\PointsProductReadRepository;
use RedJasmine\PointsMall\Infrastructure\Repositories\Eloquent\PointProductCategoryRepository;
use RedJasmine\PointsMall\Infrastructure\Repositories\Eloquent\PointsExchangeOrderRepository;
use RedJasmine\PointsMall\Infrastructure\Repositories\Eloquent\PointsProductRepository;
use RedJasmine\PointsMall\Infrastructure\Services\OrderServiceIntegration;
use RedJasmine\PointsMall\Infrastructure\Services\PaymentServiceIntegration;
use RedJasmine\PointsMall\Infrastructure\Services\ProductServiceIntegration;
use RedJasmine\PointsMall\Infrastructure\Services\WalletServiceIntegration;

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

        $this->app->bind(PointProductCategoryRepositoryInterface::class, PointProductCategoryRepository::class);
        $this->app->bind(PointProductCategoryReadRepositoryInterface::class, PointProductCategoryReadRepository::class);

        $this->app->bind(
            PointsExchangeOrderRepositoryInterface::class,
            PointsExchangeOrderRepository::class
        );

        $this->app->bind(
            PointsExchangeOrderReadRepositoryInterface::class,
            PointsExchangeOrderReadRepository::class
        );


        $this->app->bind(ProductServiceInterface::class, ProductServiceIntegration::class);
        $this->app->bind(WalletServiceInterface::class, WalletServiceIntegration::class);
        $this->app->bind(OrderServiceInterface::class, OrderServiceIntegration::class);
        $this->app->bind(PaymentServiceInterface::class, PaymentServiceIntegration::class);
    }

    public function boot() : void
    {
    }
}
