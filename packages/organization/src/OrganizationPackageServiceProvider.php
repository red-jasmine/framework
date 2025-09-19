<?php

namespace RedJasmine\Organization;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class OrganizationPackageServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package) : void
    {
        $package
            ->name('red-jasmine-organization')
            ->hasConfigFile()
            ->hasTranslations()
            ->hasMigrations([
                'create_organizations_table',
                'create_departments_table',
                'create_positions_table',
                'create_members_table',
                'create_member_departments_table',
                'create_member_positions_table',
                'create_department_managers_table',
            ])
            ->hasRoutes(['api'])
            ->runsMigrations();
    }
}


