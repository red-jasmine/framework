<?php

namespace RedJasmine\Order;

use Illuminate\Database\Eloquent\Relations\Relation;
use RedJasmine\Order\Domain\Models\Order;
use RedJasmine\Order\Domain\Models\OrderRefund;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;


/**
 *
 */
class OrderPackageServiceProvider extends PackageServiceProvider
{


    public static string $name = 'red-jasmine-order';

    public static string $viewNamespace = 'red-jasmine-order';


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
    }


    public function packageRegistered() : void
    {
        Relation::enforceMorphMap([
                                      'order'  => Order::class,
                                      'refund' => OrderRefund::class
                                  ]);
    }

    public function getCommands() : array
    {
        return [];

    }

    public function getMigrations() : array
    {
        return [

            'create_orders_table',
            'create_order_products_table',
            'create_order_infos_table',
            'create_order_addresses_table',
            'create_order_logistics_table',
            'create_order_payments',
            'create_order_product_infos_table',
            'create_order_refund_infos_table',
            'create_order_refunds_table',
            'create_order_card_keys_table',

        ];

    }


}
