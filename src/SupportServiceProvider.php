<?php

namespace RedJasmine\Support;


use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\ServiceProvider;
use RedJasmine\MallCore\Helpers\DomainRoute;
use RedJasmine\Support\Helpers\Blueprint;
use RedJasmine\Support\Services\RequestIDService;
use RedJasmine\Support\Services\SqlLogService;

class SupportServiceProvider extends ServiceProvider
{

    /**
     * @return void
     * @throws BindingResolutionException
     */
    public function boot() : void
    {
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'red-jasmine');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'red-jasmine');

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }

        RequestIDService::boot();
        DomainRoute::boot();
        SqlLogService::boot();

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
                             __DIR__ . '/../config/support.php' => config_path('support.php'),
                         ], 'support.config');

        // Publishing the views.
        /*$this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/red-jasmine'),
        ], 'support.views');*/

        // Publishing assets.
        /*$this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/red-jasmine'),
        ], 'support.views');*/

        // Publishing the translation files.
        /*$this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/red-jasmine'),
        ], 'support.views');*/

        // Registering package commands.
        // $this->commands([]);
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register() : void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/support.php', 'red-jasmine.support');

        // Register the service the package provides.
        $this->app->singleton('support', function ($app) {
            return new Support;
        });
        DomainRoute::register();
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [ 'support' ];
    }
}
