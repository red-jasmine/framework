<?php

namespace RedJasmine\Product;

use Illuminate\Support\ServiceProvider;
use RedJasmine\Product\Products\ProductService;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use RedJasmine\Product\Database\Seeders\ProductPackageSeeder;

class ProductPackageServiceProvider extends PackageServiceProvider
{
    public static string $name = 'red-jasmine-product';

    public static string $viewNamespace = 'red-jasmine-product';


    public function configurePackage(Package $package) : void
    {

        $package->name(static::$name)
                ->hasCommands($this->getCommands())
                ->runsMigrations()
                ->hasInstallCommand(function (InstallCommand $command) {
                    $command
                        ->publishConfigFile()
//                        ->publishMigrations()
                        ->askToRunMigrations()
                        ->endWith(function (InstallCommand $command) {
                            if ($command->confirm('Seed demo data')) {
                                $command->call('db:seed', [ '--class' => ProductPackageSeeder::class ]);
                            }

                        })
                        ->askToStarRepoOnGitHub('red-jasmine/product');
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


    public function getCommands() : array
    {
        return [];

    }

    public function getMigrations() : array
    {
        return [
            'create_brands_table',
            'create_product_categories_table',
            'create_product_channel_stocks_table',
            'create_product_infos_table',
            'create_product_properties_table',
            'create_product_property_groups_table',
            'create_product_property_values_table',
            'create_product_groups_table',
            'create_product_series_products_table',
            'create_product_series_table',
            'create_product_skus_table',
            'create_product_stock_logs_table',
            'create_products_table',
            'create_product_extend_group_pivots_table'
        ];

    }

}
