<?php

namespace RedJasmine\Card;

use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class CardServicePackageProvider extends PackageServiceProvider
{
    public static string $name = 'red-jasmine-card';

    public static string $viewNamespace = 'red-jasmine-card';


    public function configurePackage(Package $package) : void
    {

        $package->name(static::$name)
                ->hasCommands($this->getCommands())
                ->runsMigrations()
                ->hasInstallCommand(function (InstallCommand $command) {
                    $command
                        ->publishConfigFile()
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


    public function getCommands() : array
    {
        return [];
    }

    public function getMigrations() : array
    {
        return [
            'create_card_group_bind_products_table',
            'create_card_groups_table',
            'create_cards_table',
        ];
    }


}
