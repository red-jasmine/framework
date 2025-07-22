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
                'create_points_exchange_orders_table',
            ])
            ->hasRoutes([
                'api' => __DIR__ . '/../routes/api.php',
                'web' => __DIR__ . '/../routes/web.php',
            ]);
    }

    public function packageRegistered(): void
    {
        
    }

    public function packageBooted(): void
    {
        // 包启动时的逻辑
    }
} 