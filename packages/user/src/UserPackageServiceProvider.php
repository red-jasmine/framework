<?php

namespace RedJasmine\User;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use RedJasmine\User\Commands\UserCommand;

class UserPackageServiceProvider extends PackageServiceProvider
{
    public static string $name = 'red-jasmine-user';

    public static string $viewNamespace = 'red-jasmine-user';

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
            ->hasTranslations()
            ->hasViews()
            ->hasRoutes(['api'])
            ->hasMigrations([
                'create_user_table',
            ])
            ->runsMigrations()
            ;
    }
}
