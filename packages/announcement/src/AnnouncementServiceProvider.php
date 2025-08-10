<?php

namespace RedJasmine\Announcement;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
class AnnouncementServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package) : void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('red-jasmine-announcement')
            ->hasConfigFile()
            ->hasViews()
            ->hasTranslations()
            ->hasMigrations([
                'create_announcement_categories_table',
                'create_announcements_table',
            ])
            ->runsMigrations()
            ->hasRoutes(['api'])
            ->runsMigrations();
    }
}
