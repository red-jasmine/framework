<?php

namespace RedJasmine\ResourceUsage;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use RedJasmine\ResourceUsage\Commands\ResourceUsageCommand;

class ResourceUsagePackageServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('resource-usage')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_resource_usage_table')
            ->runsMigrations()
            ->hasCommand(ResourceUsageCommand::class);
    }


}
