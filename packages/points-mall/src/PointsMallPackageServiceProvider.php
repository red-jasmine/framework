<?php

namespace RedJasmine\PointsMall;

use Illuminate\Support\ServiceProvider;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class PointsMallPackageServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('points-mall')
            ->hasConfigFile()
            ->hasMigrations([
                'create_points_products_table',
                'create_points_product_categories_table',
                'create_points_exchange_orders_table',
            ])
            ->hasRoutes([
                'api' => __DIR__ . '/../routes/api.php',
                'web' => __DIR__ . '/../routes/web.php',
            ]);
    }

    public function packageRegistered(): void
    {
        // 注册仓库绑定
        $this->app->bind(
            \RedJasmine\PointsMall\Domain\Repositories\PointsProductRepositoryInterface::class,
            \RedJasmine\PointsMall\Infrastructure\Repositories\Eloquent\PointsProductRepository::class
        );

        $this->app->bind(
            \RedJasmine\PointsMall\Domain\Repositories\PointsProductReadRepositoryInterface::class,
            \RedJasmine\PointsMall\Infrastructure\ReadRepositories\Mysql\PointsProductReadRepository::class
        );

        $this->app->bind(
            \RedJasmine\PointsMall\Domain\Repositories\PointsExchangeOrderRepositoryInterface::class,
            \RedJasmine\PointsMall\Infrastructure\Repositories\Eloquent\PointsExchangeOrderRepository::class
        );

        $this->app->bind(
            \RedJasmine\PointsMall\Domain\Repositories\PointsExchangeOrderReadRepositoryInterface::class,
            \RedJasmine\PointsMall\Infrastructure\ReadRepositories\Mysql\PointsExchangeOrderReadRepository::class
        );

        // 注册外部服务集成
        $this->app->singleton(
            \RedJasmine\PointsMall\Infrastructure\Services\ProductServiceIntegration::class
        );

        $this->app->singleton(
            \RedJasmine\PointsMall\Infrastructure\Services\WalletServiceIntegration::class
        );

        $this->app->singleton(
            \RedJasmine\PointsMall\Infrastructure\Services\OrderServiceIntegration::class
        );

        $this->app->singleton(
            \RedJasmine\PointsMall\Infrastructure\Services\PaymentServiceIntegration::class
        );
    }

    public function packageBooted(): void
    {
        // 包启动时的逻辑
    }
} 