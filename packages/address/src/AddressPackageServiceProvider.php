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
                ->hasMigrations([
                    'create_addresses_table',
                ])
                ->runsMigrations();


    }
}
