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
            ;
    }

    public function packageRegistered()
    {
        $this->app->register(ShoppingCartApplicationServiceProvider::class);
    }
}
