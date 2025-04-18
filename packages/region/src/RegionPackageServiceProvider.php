<?php

namespace RedJasmine\Region;

use RedJasmine\Product\Database\Seeders\ProductPackageSeeder;
use RedJasmine\Region\Commands\CrawlDataCommand;
use RedJasmine\Region\Commands\OptimizeCommand;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class RegionPackageServiceProvider extends PackageServiceProvider
{
    public static string $name = 'red-jasmine-region';

    public static string $viewNamespace = 'red-jasmine-region';


    public function configurePackage(Package $package) : void
    {

        $package->name(static::$name)
                ->hasConfigFile()
                ->hasRoutes(['api'])
                ->hasMigrations([
                    'create_countries_table',
                    'create_regions_table'

                ])
                ->runsMigrations();


    }


}
