<?php

namespace RedJasmine\Invitation;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

/**
 * 邀请包服务提供者
 */
class InvitationPackageServiceProvider extends PackageServiceProvider
{


    public function configurePackage(Package $package) : void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('red-jasmine-invitation')
            ->hasConfigFile()
            ->hasRoutes([
                'api',
                'web',
            ])
            ->hasTranslations()
            ->hasMigrations([
                'create_invitation_codes_table',
                'create_invitation_records_table',
            ])
            ->runsMigrations();
    }


} 