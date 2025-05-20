<?php

namespace RedJasmine\Admin;

use RedJasmine\Admin\Domain\Models\Permission;
use RedJasmine\Admin\Domain\Models\Role;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use RedJasmine\Admin\Commands\AdminCommand;

class AdminPackageServiceProvider extends PackageServiceProvider
{

    public function packageBooted()
    {
        app(\Spatie\Permission\PermissionRegistrar::class)
            ->setPermissionClass(Permission::class)
            ->setRoleClass(Role::class);
    }
    public function configurePackage(Package $package) : void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('red-jasmine-admin')
            ->hasConfigFile()
            //->hasViews()
            ->hasMigrations([
                'create_admin_table','create_permission_tables'
            ])
            ->runsMigrations();
    }
}
