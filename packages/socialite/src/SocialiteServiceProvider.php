<?php

namespace RedJasmine\Socialite;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use RedJasmine\Socialite\Commands\SocialiteCommand;

class SocialiteServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
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
            ->hasMigration('create_socialite_table')
            ->hasCommand(SocialiteCommand::class);
    }
}
