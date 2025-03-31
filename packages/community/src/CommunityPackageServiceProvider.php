<?php

namespace RedJasmine\Community;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use RedJasmine\Community\Commands\CommunityCommand;

class CommunityPackageServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('red-jasmine-community')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigrations([])
            ->runsMigrations()
          ;
    }
}
