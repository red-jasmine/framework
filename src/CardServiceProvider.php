<?php

namespace RedJasmine\Card;

use Illuminate\Support\ServiceProvider;

class CardServiceProvider extends ServiceProvider
{
    public function register() : void
    {

        $this->mergeConfigFrom(__DIR__ . '/../config/card.php', 'red-jasmine');

        $this->app->singleton('card', function ($app) {
            return new Card;
        });
    }

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
                             __DIR__ . '/../config/card.php' => config_path('red-jasmine.card.php'),
                         ], 'red-jasmine.card.config');

        // Publishing the views.
        /*$this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/red-jasmine'),
        ], 'captcha.views');*/

        // Publishing assets.
        /*$this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/red-jasmine'),
        ], 'captcha.views');*/

        // Publishing the translation files.
        /*$this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/red-jasmine'),
        ], 'captcha.views');*/

        // Registering package commands.
        // $this->commands([]);
    }
}
