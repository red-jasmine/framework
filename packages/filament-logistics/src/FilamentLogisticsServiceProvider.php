<?php

namespace RedJasmine\FilamentLogistics;

use Filament\Support\Assets\Asset;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Facades\FilamentIcon;
use Illuminate\Filesystem\Filesystem;
use RedJasmine\FilamentLogistics\Commands\FilamentLogisticsCommand;
use RedJasmine\FilamentLogistics\Testing\TestsFilamentLogistics;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentLogisticsServiceProvider extends PackageServiceProvider
{
    public static string $name = 'red-jasmine-filament-logistics';

    public static string $viewNamespace = 'filament-logistics';

    public function configurePackage(Package $package) : void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package->name(static::$name)
                ->hasCommands($this->getCommands());

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
            foreach (app(Filesystem::class)->files(__DIR__.'/../stubs/') as $file) {
                $this->publishes([
                    $file->getRealPath() => base_path("stubs/filament-logistics/{$file->getFilename()}"),
                ], 'filament-logistics-stubs');
            }
        }

        // Testing

    }

    protected function getAssetPackageName() : ?string
    {
        return 'red-jasmine/filament-logistics';
    }

    /**
     * @return array<Asset>
     */
    protected function getAssets() : array
    {
        return [
            // AlpineComponent::make('filament-logistics', __DIR__ . '/../resources/dist/components/filament-logistics.js'),
            // Css::make('filament-logistics-styles', __DIR__ . '/../resources/dist/filament-logistics.css'),
            //Js::make('filament-logistics-scripts', __DIR__ . '/../resources/dist/filament-logistics.js'),
        ];
    }

    /**
     * @return array<class-string>
     */
    protected function getCommands() : array
    {
        return [
            // FilamentLogisticsCommand::class,
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
            'create_filament-logistics_table',
        ];
    }
}
