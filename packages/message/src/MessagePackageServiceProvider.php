<?php

namespace RedJasmine\Message;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class MessagePackageServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('red-jasmine-message')
            ->hasConfigFile('message')
            ->hasTranslations()
            ->hasMigrations([
                'create_messages_table',
                'create_message_categories_table',
                'create_message_templates_table',
            ])
            ->hasRoutes(['message'])
            ->runsMigrations();
    }
}
