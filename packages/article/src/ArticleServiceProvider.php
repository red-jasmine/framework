<?php

namespace RedJasmine\Article;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use RedJasmine\Article\Commands\ArticleCommand;

class ArticleServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('red-jasmine-article')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigrations([

            ])
            ->runsMigrations()
        ;
    }
}
