<?php

namespace RedJasmine\Wallet;

use RedJasmine\Wallet\Domain\Contracts\PaymentServiceInterface;
use RedJasmine\Wallet\Infrastructure\Services\PaymentServiceIntegration;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class WalletServicePackageProvider extends PackageServiceProvider
{

    public static string $name = 'red-jasmine-wallet';

    public static string $viewNamespace = 'red-jasmine-wallet';


    public function configurePackage(Package $package) : void
    {

        $package
            ->name('red-jasmine-wallet')
            ->hasConfigFile()
            ->hasViews()
            ->hasTranslations()
            ->hasMigrations([
                'create_wallets_table',
                'create_wallet_transactions_table',
                'create_wallet_recharges_table',
                'create_wallet_withdrawals_table',
            ])
            ->runsMigrations();


    }

    public function packageRegistered() : void
    {
        // 对 配置进行

        foreach ($this->app->config->get('red-jasmine-wallet.wallets', []) as $type => $walletConfig) {
            $this->app->config->set('money.currencies.custom.'.$walletConfig['currency'], $walletConfig);
        }


        $this->app->bind(PaymentServiceInterface::class, PaymentServiceIntegration::class);


    }

}
