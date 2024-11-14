<?php

namespace RedJasmine\Payment;

use Illuminate\Support\ServiceProvider;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class PaymentPackageServiceProvider extends PackageServiceProvider
{


    public static string $name = 'red-jasmine-order';

    public static string $viewNamespace = 'red-jasmine-order';

    public function configurePackage(Package $package) : void
    {

        $package->name(static::$name)
                ->hasCommands($this->getCommands())
                ->runsMigrations()
                ->hasInstallCommand(function (InstallCommand $command) {
                    $command
                        ->publishConfigFile()
//                        ->publishMigrations()
                        ->askToRunMigrations();
                });

        $configFileName = $package->shortName();


        if (file_exists($package->basePath("/../config/{$configFileName}.php"))) {
            $package->hasConfigFile();
        }

        if (file_exists($package->basePath('/../database/migrations'))) {
            $package->hasMigrations($this->getMigrations());
        }

        if (file_exists($package->basePath('/../resources/lang'))) {
            $package->hasTranslations();
        }

        if (file_exists($package->basePath('/../resources/views'))) {
            $package->hasViews(static::$viewNamespace);
        }
    }

    public function packageRegistered() : void
    {

    }

    public function packageBooted() : void
    {

    }

    public function getMigrations() : array
    {
        return [
        ];
    }

    public function getCommands() : array
    {
        return [];

    }

}
