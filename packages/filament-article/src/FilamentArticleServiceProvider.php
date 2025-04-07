<?php

namespace RedJasmine\FilamentArticle;

use Filament\Support\Assets\Asset;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Facades\FilamentIcon;
use Illuminate\Filesystem\Filesystem;
use RedJasmine\FilamentArticle\Commands\FilamentArticleCommand;
use RedJasmine\FilamentArticle\Testing\TestsFilamentArticle;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentArticleServiceProvider extends PackageServiceProvider
{
    public static string $name = 'red-jasmine-filament-article';

    public static string $viewNamespace = 'red-jasmine-filament-article';

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
                        // ->publishMigrations()
                        // ->askToRunMigrations()
                        //->askToStarRepoOnGitHub('red-jasmine/filament-article')
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
        if (app()->runningInConsole()) {
            foreach (app(Filesystem::class)->files(__DIR__.'/../stubs/') as $file) {
                $this->publishes([
                    $file->getRealPath() => base_path("stubs/filament-article/{$file->getFilename()}"),
                ], 'filament-article-stubs');
            }
        }

        // Testing
        //Testable::mixin(new TestsFilamentArticle);
    }

    protected function getAssetPackageName() : ?string
    {
        return 'red-jasmine/filament-article';
    }

    /**
     * @return array<Asset>
     */
    protected function getAssets() : array
    {
        return [
            // AlpineComponent::make('filament-article', __DIR__ . '/../resources/dist/components/filament-article.js'),
            //Css::make('filament-article-styles', __DIR__.'/../resources/dist/filament-article.css'),
            //Js::make('filament-article-scripts', __DIR__.'/../resources/dist/filament-article.js'),
        ];
    }

    /**
     * @return array<class-string>
     */
    protected function getCommands() : array
    {
        return [

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
