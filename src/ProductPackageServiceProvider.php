<?php

namespace RedJasmine\Product;

use Illuminate\Support\ServiceProvider;
use RedJasmine\Product\Products\ProductService;

class ProductPackageServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot() : void
    {

        $this->loadTranslationsFrom(__DIR__ . '/../lang', 'red-jasmine.product');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'red-jasmine');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');



        // $this->loadRoutesFrom(__DIR__.'/routes.php');

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register() : void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/product.php', 'red-jasmine.product');


    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [ ];
    }

    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole() : void
    {
        // Publishing the configuration file.
        $this->publishes([
                             __DIR__ . '/../config/product.php' => config_path('red-jasmine/product.php'),
                         ], 'red-jasmine.product.config');

        // Publishing the views.
        /*$this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/red-jasmine'),
        ], 'product.views');*/

        // Publishing assets.
        /*$this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/red-jasmine'),
        ], 'product.views');*/

        // Publishing the translation files.
        $this->publishes([
                             __DIR__ . '/../lang' => $this->app->langPath('vendor/red-jasmine/product'),
                         ], 'red-jasmine.product.lang');

        // Registering package commands.
        // $this->commands([]);
    }
}
