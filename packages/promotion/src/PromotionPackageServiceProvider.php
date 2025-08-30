<?php

namespace RedJasmine\Promotion;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class PromotionPackageServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('red-jasmine-promotion')
            ->hasConfigFile()
            ->hasMigrations([
                '2024_01_01_000001_create_promotion_activities_table',
                '2024_01_01_000002_create_promotion_activity_products_table',
                '2024_01_01_000003_create_promotion_activity_skus_table',
                '2024_01_01_000004_create_promotion_activity_orders_table',
            ])
            ->runsMigrations();
    }
}
