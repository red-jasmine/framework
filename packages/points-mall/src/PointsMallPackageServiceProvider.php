<?php

namespace RedJasmine\PointsMall;

use RedJasmine\PointsMall\Application\PointsMallApplicationServiceProvider;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class PointsMallPackageServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package) : void
    {
        $package
            ->name('red-jasmine-points-mall')
            ->hasConfigFile()
            ->hasMigrations([
                'create_points_products_table',
                'create_points_product_categories_table',
                'create_points_exchange_orders_table',
            ])
            ->hasRoutes(['api'])
            ->runsMigrations();

        $this->app->register(PointsMallApplicationServiceProvider::class);
    }


}