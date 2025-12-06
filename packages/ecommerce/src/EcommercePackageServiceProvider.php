<?php

namespace RedJasmine\Ecommerce;

use RedJasmine\Ecommerce\Domain\Models\Casts\PromiseServicesCastTransformer;
use RedJasmine\Ecommerce\Domain\Models\Casts\PromiseServiceValueCastTransformer;
use RedJasmine\Ecommerce\Domain\Models\ValueObjects\PromiseServices;
use RedJasmine\Ecommerce\Domain\Models\ValueObjects\PromiseServiceValue;
use RedJasmine\Support\Domain\Contracts\UserInterface;
use RedJasmine\Support\Domain\Models\Casts\UserInterfaceCastTransformer;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class EcommercePackageServiceProvider extends PackageServiceProvider
{
    public static string $name = 'red-jasmine-ecommerce';

    public static string $viewNamespace = 'red-jasmine-ecommerce';


    public function configurePackage(Package $package) : void
    {
        /*
        * This class is a Package Service Provider
        *
        * More info: https://github.com/spatie/laravel-package-tools
        */
        $package->name(static::$name)
                ->hasCommands($this->getCommands())
                ->hasInstallCommand(function (InstallCommand $command) {
                    $command
                        ->publishConfigFile()
                        ->publishMigrations()
                        ->askToRunMigrations()
                        ->askToStarRepoOnGitHub('red-jasmine/ecommerce');
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

    protected function getCommands() : array
    {
        return [

        ];
    }

    protected function getMigrations() : array
    {

        return [];
    }

    public function packageRegistered() : void
    {


    }

    public function packageBooted() : void
    {
        $config = $this->app->make('config');


        $config->set('data.casts.'.PromiseServiceValue::class, PromiseServiceValueCastTransformer::class);
        $config->set('data.transformers.'.PromiseServiceValue::class, PromiseServiceValueCastTransformer::class);


        $config->set('data.casts.'.PromiseServices::class, PromiseServicesCastTransformer::class);
        $config->set('data.transformers.'.PromiseServices::class, PromiseServicesCastTransformer::class);

        $config->set('data.casts.'.UserInterface::class, UserInterfaceCastTransformer::class);
        $config->set('data.transformers.'.UserInterface::class, UserInterfaceCastTransformer::class);

    }
}
