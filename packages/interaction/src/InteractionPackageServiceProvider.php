<?php

namespace RedJasmine\Interaction;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use RedJasmine\Interaction\Commands\InteractionCommand;

class InteractionPackageServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package) : void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('red-jasmine-interaction')
            ->hasConfigFile()
            ->hasRoutes(['api'])
            ->hasViews()
            ->hasMigrations([
                'create_interaction_statistics_table',
                'create_interaction_records_table',
                'create_interaction_record_comments_table',
            ])
            ->runsMigrations()
        ;
    }
}
