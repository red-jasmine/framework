<?php

namespace RedJasmine\Message;

use RedJasmine\Message\Application\MessageApplicationServiceProvider;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class MessagePackageServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package) : void
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
                '2024_01_01_000001_create_messages_table',
                '2024_01_01_000002_create_message_categories_table',
                '2024_01_01_000003_create_message_templates_table',
            ])
            ->hasRoutes(['message'])
            ->runsMigrations();
    }

    public function packageRegistered() : void
    {
        $this->app->register(MessageApplicationServiceProvider::class);
    }
}
