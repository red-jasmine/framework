<?php

namespace RedJasmine\Logistics;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LogisticsPackageServiceProvider extends PackageServiceProvider
{

    public function configurePackage(Package $package) : void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('red-jasmine-logistics')
            ->hasConfigFile()
            ->hasTranslations()
            ->hasViews()
            ->hasMigrations([
                'create_logistics_companies_table',
                'create_logistics_freight_templates_table'
            ])
            ->runsMigrations();
    }


}
