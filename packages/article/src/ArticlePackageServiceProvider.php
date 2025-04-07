<?php

namespace RedJasmine\Article;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use RedJasmine\Article\Commands\ArticleCommand;

class ArticlePackageServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package) : void
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
            ->hasTranslations()
            ->hasMigrations([
                'create_articles_table',
                'create_articles_extension_table',
                'create_article_categories_table',
                'create_article_tags_table',
                'create_article_tag_pivots_table',
            ])
            ->hasRoutes(['api'])
            ->runsMigrations();
    }
}
