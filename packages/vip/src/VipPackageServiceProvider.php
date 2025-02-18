<?php

namespace RedJasmine\Vip;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use RedJasmine\Vip\Commands\VipCommand;

class VipPackageServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package) : void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('red-jasmine-vip')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigrations([
                'create_user_vips_table',
                'create_vips_table',
            ])
            ->runsMigrations();
    }
}
