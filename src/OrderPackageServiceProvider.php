<?php

namespace RedJasmine\Order;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;
use RedJasmine\Order\Domain\Models\Order;
use RedJasmine\Order\Domain\Models\OrderRefund;


/**
 *
 */
class OrderPackageServiceProvider extends ServiceProvider
{


    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot() : void
    {

        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'red-jasmine');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'red-jasmine');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }

        Relation::enforceMorphMap([
                                      'order'  => Order::class,
                                      'refund' => OrderRefund::class
                                  ]);
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register() : void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/order.php', 'red-jasmine.order');


    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [ 'order' ];
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
                             __DIR__ . '/../config/order.php' => config_path('order.php'),
                         ], 'red-jasmine.order.config');

        // Publishing the views.
        /*$this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/red-jasmine'),
        ], 'order.views');*/

        // Publishing assets.
        /*$this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/red-jasmine'),
        ], 'order.views');*/

        // Publishing the translation files.
        /*$this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/red-jasmine'),
        ], 'order.views');*/

        // Registering package commands.
        // $this->commands([]);
    }
}
