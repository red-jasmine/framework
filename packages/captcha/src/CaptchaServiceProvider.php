<?php

namespace RedJasmine\Captcha;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class CaptchaServiceProvider extends PackageServiceProvider
{

    public static string $name = 'red-jasmine-captcha';

    public static string $viewNamespace = 'red-jasmine-captcha';


    public function configurePackage(Package $package) : void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('red-jasmine-captcha')
            ->hasConfigFile()
            ->hasViews()
            ->hasTranslations()
            ->hasMigrations([
                'create_captchas_table',
            ])
            ->runsMigrations();
    }

}
