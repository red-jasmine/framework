<?php

namespace RedJasmine\Shopping;


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
            ->hasViews();
    }
}
