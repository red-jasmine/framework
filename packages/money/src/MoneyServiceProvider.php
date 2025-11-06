<?php

namespace RedJasmine\Money;

use Illuminate\Support\ServiceProvider;
use RedJasmine\Money\Currencies\AggregateCurrencies;

class MoneyServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // 合并配置
        $this->mergeConfigFrom(
            __DIR__ . '/../config/money.php',
            'money'
        );

        // 注册货币聚合器为单例
        $this->app->singleton(AggregateCurrencies::class, function ($app) {
            $config = $app['config']->get('money.currencies', []);
            return AggregateCurrencies::make($config);
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // 发布配置文件
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/money.php' => config_path('money.php'),
            ], 'money-config');

            // 发布语言文件
            $this->publishes([
                __DIR__ . '/../resources/lang' => $this->app->langPath('vendor/money'),
            ], 'money-lang');
        }

        // 加载语言文件
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'money');
    }

    /**
     * Get the services provided by the provider.
     */
    public function provides(): array
    {
        return [
            AggregateCurrencies::class,
        ];
    }
}

