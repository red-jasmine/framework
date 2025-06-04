<?php

namespace RedJasmine\Distribution;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;


class DistributionPackageServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('red-jasmine-distribution')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_distribution_table')
            ->hasCommand();
    }
}
