<?php

namespace RedJasmine\UserCore;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class UserCorePackageServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package) : void
    {
        $package
            ->name('red-jasmine-user-core')
            ->hasConfigFile()
            ->hasMigrations()
            ->hasTranslations()
            ->hasTranslations();
    }


}
