<?php

namespace RedJasmine\Wallet;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class WalletServicePackageProvider extends PackageServiceProvider
{

    public static string $name = 'red-jasmine-wallet';

    public static string $viewNamespace = 'red-jasmine-wallet';


    public function configurePackage(Package $package) : void
    {
        /*
    * This class is a Package Service Provider
    *
    * More info: https://github.com/spatie/laravel-package-tools
    */
        $package
            ->name('red-jasmine-wallet')
            ->hasConfigFile()
            ->hasViews()
            ->hasTranslations()
            ->runsMigrations();


        if (file_exists($package->basePath('/../database/migrations'))) {
            $package->hasMigrations($this->getMigrations());
        }
    }

    public function getMigrations() : array
    {
        return [
            'create_wallets_table',
            'create_wallet_transactions_table',
            'create_wallet_recharges_table',
            'create_wallet_withdrawals_table',
        ];

    }
    //
    //
    // /**
    //  * Perform post-registration booting of services.
    //  *
    //  * @return void
    //  */
    // public function boots() : void
    // {
    //     // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'red-jasmine');
    //     // $this->loadViewsFrom(__DIR__.'/../resources/views', 'red-jasmine');
    //     $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    //     // $this->loadRoutesFrom(__DIR__.'/routes.php');
    //
    //     // Publishing is only necessary when using the CLI.
    //     if ($this->app->runningInConsole()) {
    //         $this->bootForConsole();
    //     }
    // }
    //
    // /**
    //  * Register any package services.
    //  *
    //  * @return void
    //  */
    // public function register() : void
    // {
    //     $this->mergeConfigFrom(__DIR__.'/../config/wallet.php', 'red-jasmine.wallet');
    //
    //     // Register the service the package provides.
    //     $this->app->singleton('wallet', function ($app) {
    //         return new WalletService;
    //     });
    // }
    //
    // /**
    //  * Get the services provided by the provider.
    //  *
    //  * @return array
    //  */
    // public function provides()
    // {
    //     return ['wallet'];
    // }
    //
    // /**
    //  * Console-specific booting.
    //  *
    //  * @return void
    //  */
    // protected function bootForConsole() : void
    // {
    //     // Publishing the configuration file.
    //     $this->publishes([
    //         __DIR__.'/../config/wallet.php' => config_path('red-jasmine/wallet.php'),
    //     ], 'red-jasmine.wallet.config');
    //
    //     // Publishing the views.
    //     /*$this->publishes([
    //         __DIR__.'/../resources/views' => base_path('resources/views/vendor/red-jasmine'),
    //     ], 'wallet.views');*/
    //
    //     // Publishing assets.
    //     /*$this->publishes([
    //         __DIR__.'/../resources/assets' => public_path('vendor/red-jasmine'),
    //     ], 'wallet.views');*/
    //
    //     // Publishing the translation files.
    //     /*$this->publishes([
    //         __DIR__.'/../resources/lang' => resource_path('lang/vendor/red-jasmine'),
    //     ], 'wallet.views');*/
    //
    //     // Registering package commands.
    //     // $this->commands([]);
    // }
}
