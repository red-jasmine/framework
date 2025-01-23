<?php

namespace RedJasmine\User;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use RedJasmine\User\Commands\UserCommand;

class UserPackageServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package) : void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('red-jasmine-user')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_user_table');
    }
}
