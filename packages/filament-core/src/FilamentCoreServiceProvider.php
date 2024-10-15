<?php

namespace RedJasmine\FilamentCore;

use Filament\Forms\Components\Field;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\ToggleButtons;
use Filament\Support\Assets\Asset;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Facades\FilamentIcon;
use Filament\Tables\Columns\Column;
use Filament\Tables\Columns\TextColumn;
use RedJasmine\FilamentCore\Commands\FilamentCoreCommand;
use RedJasmine\FilamentCore\Testing\TestsFilamentCore;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentCoreServiceProvider extends PackageServiceProvider
{
    public static string $name = 'red-jasmine-filament-core';

    public static string $viewNamespace = 'red-jasmine-filament-core';

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
                        ->askToStarRepoOnGitHub('red-jasmine/filament-core');
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

    /**
     * @return array<class-string>
     */
    protected function getCommands() : array
    {
        return [
            FilamentCoreCommand::class,
        ];
    }

    /**
     * @return array<string>
     */
    protected function getMigrations() : array
    {
        return [

        ];
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


        Column::macro('useEnum', function () {

            if (method_exists($this, 'badge')) {
                $this->badge();
            }
            if (method_exists($this, 'formatStateUsing')) {
                $this->formatStateUsing(fn($state) => $state->getLabel());
            }
            if (method_exists($this, 'color')) {
                $this->color(fn($state) => $state->getColor());
            }
            if (method_exists($this, 'icon')) {
                $this->icon(fn($state) => $state->getIcon());
            }
            return $this;

        });



        Field::macro('defaultZero', function () {
            $this->default(0)
                 ->formatStateUsing(fn($state) => $state === 0 ? null : $state)
                 ->mutateDehydratedStateUsing(fn($state) => $state ?? 0);
            return $this;
        });

        Field::macro('useEnum', function (string $enumClassName) {
            $this->enum($enumClassName);
            if (method_exists($this, 'options')) {
                $this->options($enumClassName::options());
            }
            if (method_exists($this, 'colors')) {
                $this->colors($enumClassName::colors());
            }
            if (method_exists($this, 'icons')) {
                $this->icons($enumClassName::icons());
            }
            return $this;
        });


    }

    /**
     * @return array<Asset>
     */
    protected function getAssets() : array
    {
        return [
            // AlpineComponent::make('filament-core', __DIR__ . '/../resources/dist/components/filament-core.js'),
            //Css::make('filament-core-styles', __DIR__ . '/../resources/dist/filament-core.css'),
            //Js::make('filament-core-scripts', __DIR__ . '/../resources/dist/filament-core.js'),
        ];
    }

    protected function getAssetPackageName() : ?string
    {
        return 'red-jasmine/filament-core';
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
}
