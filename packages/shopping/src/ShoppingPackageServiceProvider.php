<?php

namespace RedJasmine\Shopping;


use RedJasmine\Shopping\Application\ShoppingCartApplicationServiceProvider;
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
            ->hasMigrations([
                '2024_01_01_000001_create_shopping_carts_table',
                '2024_01_01_000002_create_shopping_cart_products_table',
            ])
            ->runsMigrations();
    }

    public function packageRegistered() : void
    {
        $this->app->register(ShoppingCartApplicationServiceProvider::class);
    }
}
