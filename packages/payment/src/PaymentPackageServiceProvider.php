<?php

namespace RedJasmine\Payment;

use Illuminate\Support\ServiceProvider;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class PaymentPackageServiceProvider extends PackageServiceProvider
{


    public static string $name = 'red-jasmine-payment';

    public static string $viewNamespace = 'red-jasmine-payment';

    public function configurePackage(Package $package) : void
    {

        $package->name(static::$name)
                ->hasCommands($this->getCommands())
                ->runsMigrations()
                ->hasInstallCommand(function (InstallCommand $command) {
                    $command
                        ->publishConfigFile()
//                        ->publishMigrations()
                        ->askToRunMigrations();
                });

        $configFileName = $package->shortName();


        if (file_exists($package->basePath("/../config/{$configFileName}.php"))) {
            $package->hasConfigFile();
        }

        if (file_exists($package->basePath('/../database/migrations'))) {
            $package->hasMigrations($this->getMigrations());
        }

        if (file_exists($package->basePath('/../resources/lang'))) {
            $package->hasTranslations();
        }

        if (file_exists($package->basePath('/../resources/views'))) {
            $package->hasViews(static::$viewNamespace);
        }

        if (file_exists($package->basePath('/../routes'))) {
            $package->hasRoutes($this->getRoutes());
        }


    }

    public function packageRegistered() : void
    {


    }

    public function packageBooted() : void
    {

    }

    public function getMigrations() : array
    {
        return [
            'create_payment_methods_table',
            'create_payment_channels_table',

            'create_payment_channel_products_table',
            'create_payment_channel_product_modes_table',
            'create_payment_channel_apps_table',
            'create_payment_channel_apps_products_table',


            'create_payment_providers_table',
            'create_payment_merchants_table',
            'create_payment_merchant_apps_table',
            'create_payment_merchant_channel_app_permissions_table',


            'create_payment_trades_table',
            'create_payment_trade_extensions_table',
            'create_payment_refunds_table',
            'create_payment_refund_extensions_table',
            'create_payment_transfers_table',
            'create_payment_transfer_extensions_table',

            'create_payment_notifies_table',

        ];
    }

    /**
     * @return array<string>
     */
    protected function getRoutes() : array
    {
        return [
            'notify',
            'payer'
        ];
    }

    public function getCommands() : array
    {
        return [];

    }

}
