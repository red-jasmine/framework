<?php

namespace RedJasmine\Shop;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class ShopPackageServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('red-jasmine-shop')
            ->hasConfigFile()
            ->hasTranslations()
            ->hasRoutes(['api'])
            //->hasViews()
            ->hasMigrations([
                'create_shop_table'
            ])
            ->runsMigrations();
    }

    public function packageRegistered() : void
    {
        // 注册应用服务提供者
        $this->app->register(Application\ShopApplicationServiceProvider::class);
    }
} 