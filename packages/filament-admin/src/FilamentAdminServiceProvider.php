<?php

namespace RedJasmine\FilamentAdmin;

use BezhanSalleh\FilamentShield\Facades\FilamentShield;
use Filament\Support\Assets\Asset;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Facades\FilamentIcon;
use Illuminate\Support\Str;
use RedJadmine\FilamentAdmin\Commands\FilamentAdminCommand;
use RedJadmine\FilamentAdmin\Testing\TestsFilamentAdmin;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentAdminServiceProvider extends PackageServiceProvider
{
    public static string $name = 'red-jasmine-filament-admin';

    public static string $viewNamespace = 'red-jasmine-filament-admin';

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

        FilamentShield::configurePermissionIdentifierUsing(function ($resource) {
            return Str::of($resource::getModel())->toString();
        });

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

        }

        // Testing

    }

    protected function getAssetPackageName() : ?string
    {
        return 'red-jasmine/filament-admin';
    }

    /**
     * @return array<Asset>
     */
    protected function getAssets() : array
    {
        return [
            // AlpineComponent::make('filament-admin', __DIR__ . '/../resources/dist/components/filament-admin.js'),
            //Css::make('filament-admin-styles', __DIR__ . '/../resources/dist/filament-admin.css'),
            //Js::make('filament-admin-scripts', __DIR__ . '/../resources/dist/filament-admin.js'),
        ];
    }

    /**
     * @return array<class-string>
     */
    protected function getCommands() : array
    {
        return [
            // FilamentAdminCommand::class,
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
