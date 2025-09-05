<?php

namespace RedJasmine\FilamentOrder;

use Filament\Support\Assets\AlpineComponent;
use Filament\Support\Assets\Asset;
use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Facades\FilamentIcon;
use Illuminate\Filesystem\Filesystem;
use Livewire\Features\SupportTesting\Testable;
use Livewire\Livewire;
use RedJasmine\FilamentOrder\Livewire\OrderProducts;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use RedJasmine\FilamentOrder\Commands\FilamentOrderCommand;
use RedJasmine\FilamentOrder\Testing\TestsFilamentOrder;

class FilamentOrderServiceProvider extends PackageServiceProvider
{
    public static string $name = 'red-jasmine-filament-order';

    public static string $viewNamespace = 'red-jasmine-filament-order';

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
                        ->askToStarRepoOnGitHub('red-jasmine/filament-order');
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
    }

    public function packageBooted() : void
    {
        // Asset Registration
        FilamentAsset::register(
            $this->getAssets(),
            $this->getAssetPackageName()
        );

        FilamentAsset::registerScriptData(
            $this->getScriptData(),
            $this->getAssetPackageName()
        );

        // Icon Registration
        FilamentIcon::register($this->getIcons());

        // Handle Stubs
        if (app()->runningInConsole()) {
            foreach (app(Filesystem::class)->files(__DIR__ . '/../stubs/') as $file) {
                $this->publishes([
                                     $file->getRealPath() => base_path("stubs/filament-order/{$file->getFilename()}"),
                                 ], 'filament-order-stubs');
            }
        }

        // Testing
        Testable::mixin(new TestsFilamentOrder);



    }

    protected function getAssetPackageName() : ?string
    {
        return 'red-jasmine/filament-order';
    }

    /**
     * @return array<Asset>
     */
    protected function getAssets() : array
    {
        return [
            // AlpineComponent::make('filament-order', __DIR__ . '/../resources/dist/components/filament-order.js'),
            //Css::make('filament-order-styles', __DIR__ . '/../resources/dist/filament-order.css'),
            //Js::make('filament-order-scripts', __DIR__ . '/../resources/dist/filament-order.js'),
        ];
    }

    /**
     * @return array<class-string>
     */
    protected function getCommands() : array
    {
        return [
            FilamentOrderCommand::class,
        ];
    }

    /**
     * @return array<string>
     */
    protected function getIcons() : array
    {
        return [];
    }

    /**
     * @return array<string>
     */
    protected function getRoutes() : array
    {
        return [];
    }

    /**
     * @return array<string, mixed>
     */
    protected function getScriptData() : array
    {
        return [];
    }

    /**
     * @return array<string>
     */
    protected function getMigrations() : array
    {
        return [
            //'create_filament-order_table',
        ];
    }
}
