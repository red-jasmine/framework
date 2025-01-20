<?php

namespace RedJasmine\Socialite;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use RedJasmine\Socialite\Commands\SocialiteCommand;

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
            ->runsMigrations()
            ->hasCommand(SocialiteCommand::class);


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
