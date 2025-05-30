<?php

namespace RedJasmine\FilamentCard;

use Filament\Support\Assets\Asset;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Facades\FilamentIcon;
use Illuminate\Filesystem\Filesystem;
use RedJasmine\FilamentCard\Testing\TestsFilamentCard;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentCardServiceProvider extends PackageServiceProvider
{
    public static string $name = 'red-jasmine-filament-card';

    public static string $viewNamespace = 'red-jasmine-filament-card';

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
//                    ->publishMigrations()
//                    ->askToRunMigrations()
//                    ->askToStarRepoOnGitHub('red-jasmine/filament-card')
                    ;
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


        // Testing
        //Testable::mixin(new TestsFilamentCard);
    }

    protected function getAssetPackageName() : ?string
    {
        return 'red-jasmine/filament-card';
    }

    /**
     * @return array<Asset>
     */
    protected function getAssets() : array
    {
        return [
            // AlpineComponent::make('filament-card', __DIR__ . '/../resources/dist/components/filament-card.js'),
            // Css::make('filament-card-styles', __DIR__ . '/../resources/dist/filament-card.css'),
            // Js::make('filament-card-scripts', __DIR__ . '/../resources/dist/filament-card.js'),
        ];
    }

    /**
     * @return array<class-string>
     */
    protected function getCommands() : array
    {
        return [
            //FilamentCardCommand::class,
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

        ];
    }
}
