<?php

namespace RedJasmine\PointsMall\Application;

use Illuminate\Support\ServiceProvider;
use RedJasmine\PointsMall\Domain\Contracts\OrderServiceInterface;
use RedJasmine\PointsMall\Domain\Contracts\PaymentServiceInterface;
use RedJasmine\PointsMall\Domain\Contracts\ProductServiceInterface;
use RedJasmine\PointsMall\Domain\Contracts\WalletServiceInterface;
use RedJasmine\PointsMall\Domain\Repositories\PointProductCategoryRepositoryInterface;
use RedJasmine\PointsMall\Domain\Repositories\PointsExchangeOrderRepositoryInterface;
use RedJasmine\PointsMall\Domain\Repositories\PointsProductRepositoryInterface;
use RedJasmine\PointsMall\Infrastructure\Repositories\PointProductCategoryRepository;
use RedJasmine\PointsMall\Infrastructure\Repositories\PointsExchangeOrderRepository;
use RedJasmine\PointsMall\Infrastructure\Repositories\PointsProductRepository;
use RedJasmine\PointsMall\Infrastructure\Services\OrderServiceIntegration;
use RedJasmine\PointsMall\Infrastructure\Services\PaymentServiceIntegration;
use RedJasmine\PointsMall\Infrastructure\Services\ProductServiceIntegration;
use RedJasmine\PointsMall\Infrastructure\Services\WalletServiceIntegration;

class PointsMallApplicationServiceProvider extends ServiceProvider
{
    public function register() : void
    {
        // 统一仓库接口绑定，支持读写操作
        $this->app->bind(PointsProductRepositoryInterface::class, PointsProductRepository::class);
        $this->app->bind(PointProductCategoryRepositoryInterface::class, PointProductCategoryRepository::class);
        $this->app->bind(PointsExchangeOrderRepositoryInterface::class, PointsExchangeOrderRepository::class);

        // 外部服务集成绑定
        $this->app->bind(ProductServiceInterface::class, ProductServiceIntegration::class);
        $this->app->bind(WalletServiceInterface::class, WalletServiceIntegration::class);
        $this->app->bind(OrderServiceInterface::class, OrderServiceIntegration::class);
        $this->app->bind(PaymentServiceInterface::class, PaymentServiceIntegration::class);
    }

    public function boot() : void
    {
    }
}
