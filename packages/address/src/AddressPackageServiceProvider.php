<?php

namespace RedJasmine\Address;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class AddressPackageServiceProvider extends PackageServiceProvider
{
    public static string $name = 'red-jasmine-address';

    public static string $viewNamespace = 'red-jasmine-address';


    public function configurePackage(Package $package) : void
    {

        $package->name(static::$name)
                ->hasConfigFile()
                ->hasRoutes(['api'])
                ->hasMigrations([
                    'create_address_table',
                ])
                ->runsMigrations();


    }
}
