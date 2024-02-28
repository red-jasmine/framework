<?php

namespace RedJasmine\Support;


use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Encryption\MissingAppKeyException;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use RedJasmine\Support\Helpers\Encrypter\AES;
use RedJasmine\Support\Services\DomainRoute;
use RedJasmine\Support\Helpers\Blueprint;
use RedJasmine\Support\Services\RequestIDService;
use RedJasmine\Support\Services\SQLLogService;

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

        //RequestIDService::boot();
        DomainRoute::boot();
        SQLLogService::boot();

    }


    protected function registerAES() : void
    {
        $this->app->singleton('aes', function ($app) {
            $config = $app->make('config')->get('app');
            return new AES($this->parseKey($config));
        });
    }
    /**
     * Parse the encryption key.
     *
     * @param  array  $config
     * @return string
     */
    protected function parseKey(array $config)
    {
        if (Str::startsWith($key = $this->key($config), $prefix = 'base64:')) {
            $key = base64_decode(Str::after($key, $prefix));
        }

        return $key;
    }

    /**
     * Extract the encryption key from the given configuration.
     *
     * @param  array  $config
     * @return string
     *
     * @throws \Illuminate\Encryption\MissingAppKeyException
     */
    protected function key(array $config)
    {
        return tap($config['key'], function ($key) {
            if (empty($key)) {
                throw new MissingAppKeyException;
            }
        });
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
                             __DIR__ . '/../config/support.php' => config_path('red-jasmine/support.php'),
                         ], 'red-jasmine/support.config');

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


        DomainRoute::register();

        $this->registerAes();
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
