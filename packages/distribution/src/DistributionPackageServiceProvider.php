<?php

namespace RedJasmine\Distribution;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;


class DistributionPackageServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('red-jasmine-distribution')
            ->hasConfigFile()
            ->hasViews()
            ->hasRoutes(['api'])
            ->hasMigration('create_distribution_table')
            ->hasMigrations([
                'create_promoter_bind_users_table',
                'create_promoter_groups_table',
                'create_promoter_levels_table',
                'create_promoter_teams_table',
                'create_promoters_table',
                'create_promoter_applies_table',
            ])
            ->runsMigrations()
            ;
    }
}
