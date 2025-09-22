<?php

namespace RedJasmine\Shopping;


use RedJasmine\Shopping\Application\ShoppingCartApplicationServiceProvider;
use RedJasmine\Shopping\Infrastructure\ShoppingInfrastructureServiceProvider;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class ShoppingPackageServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package) : void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('red-jasmine-shopping')
            ->hasConfigFile()
            ->hasRoutes(['api'])
            ->runsMigrations();
    }

    public function packageRegistered() : void
    {
        // 购物车迁移至独立包，不再在此注册
        $this->app->register(ShoppingInfrastructureServiceProvider::class);
    }
}
