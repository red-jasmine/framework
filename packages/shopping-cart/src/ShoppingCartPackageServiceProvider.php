<?php

namespace RedJasmine\ShoppingCart;

use RedJasmine\ShoppingCart\Application\ShoppingCartApplicationServiceProvider;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class ShoppingCartPackageServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package) : void
    {
        $package
            ->name('red-jasmine-shopping-cart')
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
