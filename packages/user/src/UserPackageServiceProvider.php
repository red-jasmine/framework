<?php

namespace RedJasmine\User;

use RedJasmine\User\Commands\UserCommand;
use RedJasmine\User\Domain\Models\User;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

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
            ->runsMigrations();
    }

    public function packageRegistered():void
    {

        $this->app->config->set('auth.providers.users', [
            'driver' => 'eloquent',
            'model'  => User::class,
        ]);

        $this->app->config->set('auth.guards.user', [
            'driver'   => 'jwt',
            'provider' => 'users',
        ]);

    }
}
