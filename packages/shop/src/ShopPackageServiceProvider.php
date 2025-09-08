<?php

namespace RedJasmine\Shop;

use RedJasmine\Shop\Domain\Models\Shop;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class ShopPackageServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package) : void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('red-jasmine-shop')
            ->hasConfigFile()
            ->hasTranslations()
            ->hasRoutes(['api'])
            //->hasViews()
            ->hasMigrations([
                'create_shops_table'
            ])
            ->runsMigrations();
    }


    public function packageRegistered() : void
    {
        $this->app->register(Application\ShopApplicationServiceProvider::class);


        $this->app->config->set('auth.providers.shops', [
            'driver' => 'eloquent',
            'model'  => Shop::class,
        ]);

        $this->app->config->set('auth.guards.shop', [
            'driver'   => 'jwt',
            'provider' => 'shops',
        ]);

    }
} 