<?php

namespace RedJasmine\Socialite;

use RedJasmine\Socialite\Commands\SocialiteCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class SocialitePackageServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package) : void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('red-jasmine-socialite')
            ->hasConfigFile()
            ->hasViews()
            ->runsMigrations();


        if (file_exists($package->basePath('/../database/migrations'))) {

            $package->hasMigrations($this->getMigrations());
        }
    }

    public function getMigrations() : array
    {
        return [
            'create_socialite_users_table'
        ];

    }
}
