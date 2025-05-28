<?php

namespace RedJasmine\Support;


use Cknow\Money\Money;
use Illuminate\Container\Container;
use Illuminate\Encryption\MissingAppKeyException;
use Illuminate\Support\Str;
use Money\Currency;
use RedJasmine\Support\Domain\Casts\AmountCast;
use RedJasmine\Support\Domain\Casts\CurrencyCast;
use RedJasmine\Support\Domain\Casts\MoneyCast;
use RedJasmine\Support\Domain\Casts\MoneyOldCast;
use RedJasmine\Support\Domain\Models\ValueObjects\Amount;
use RedJasmine\Support\Domain\Models\ValueObjects\MoneyOld;
use RedJasmine\Support\Foundation\Hook\HookManage;
use RedJasmine\Support\Helpers\Encrypter\AES;
use RedJasmine\Support\Infrastructure\ServiceContextManage;
use RedJasmine\Support\Migration\MigrationService;
use RedJasmine\Support\Services\SQLLogService;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;


class SupportPackageServiceProvider extends PackageServiceProvider
{
    public static string $name = 'red-jasmine-support';

    public static string $viewNamespace = 'red-jasmine-support';


    public function configurePackage(Package $package) : void
    {

        $package->name(static::$name)
                ->hasCommands($this->getCommands())
                ->hasInstallCommand(function (InstallCommand $command) {
                    $command
                        ->publishConfigFile()
                        ->publishMigrations()
                        ->askToRunMigrations()
                        ->askToStarRepoOnGitHub('red-jasmine/support');
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
        $this->registerAes();

        SQLLogService::register();

        MigrationService::register();

        $this->app->singleton(ServiceContextManage::class, function () {
            return new ServiceContextManage(fn() => Container::getInstance());
        });


        $this->app->singleton('hook', function ($app) {
            return new HookManage();
        });
    }

    public function packageBooted() : void
    {

        $config = $this->app->make('config');


        $config->set('data.casts.'.Money::class, MoneyCast::class);
        $config->set('data.transformers.'.Money::class, MoneyCast::class);

        $config->set('data.casts.'.Currency::class, CurrencyCast::class);
        $config->set('data.transformers.'.Currency::class, CurrencyCast::class);

        $config->set('data.casts.'.MoneyOld::class, MoneyOldCast::class);
        $config->set('data.transformers.'.MoneyOld::class, MoneyOldCast::class);

        $config->set('data.casts.'.Amount::class, AmountCast::class);
        $config->set('data.transformers.'.Amount::class, AmountCast::class);

    }

    protected function getCommands() : array
    {
        return [

        ];
    }


    protected function registerAES() : void
    {
        $this->app->singleton('aes', function ($app) {
            $config = $app->make('config')->get('app');
            return new AES($this->parseKey($config));
        });
    }

    /**
     * Parse the encryption key.
     *
     * @param  array  $config
     *
     * @return string
     */
    protected function parseKey(array $config) : string
    {
        if (Str::startsWith($key = $this->key($config), $prefix = 'base64:')) {
            $key = base64_decode(Str::after($key, $prefix));
        }

        return $key;
    }

    /**
     * Extract the encryption key from the given configuration.
     *
     * @param  array  $config
     *
     * @return string
     *
     * @throws MissingAppKeyException
     */
    protected function key(array $config)
    {
        return tap($config['key'], function ($key) {
            if (empty($key)) {
                throw new MissingAppKeyException;
            }
        });
    }


}
