<?php

namespace RedJasmine\Wallet\Application;

use Illuminate\Support\ServiceProvider;
use RedJasmine\Wallet\Application\Listeners\WalletWithdrawalApprovalListener;
use RedJasmine\Wallet\Domain\Repositories\WalletReadRepositoryInterface;
use RedJasmine\Wallet\Domain\Repositories\WalletRepositoryInterface;
use RedJasmine\Wallet\Domain\Repositories\WalletTransactionReadRepositoryInterface;
use RedJasmine\Wallet\Domain\Repositories\WalletTransactionRepositoryInterface;
use RedJasmine\Wallet\Domain\Repositories\WalletWithdrawalRepositoryInterface;
use RedJasmine\Wallet\Infrastructure\ReadRepositories\Mysql\WalletReadRepository;
use RedJasmine\Wallet\Infrastructure\ReadRepositories\Mysql\WalletTransactionReadRepository;
use RedJasmine\Wallet\Infrastructure\Repositories\Eloquent\WalletRepository;
use RedJasmine\Wallet\Infrastructure\Repositories\Eloquent\WalletTransactionRepository;
use RedJasmine\Wallet\Infrastructure\Repositories\Eloquent\WalletWithdrawalRepository;

class WalletApplicationServiceProvider extends ServiceProvider
{
    public function register() : void
    {
        $this->app->bind(WalletRepositoryInterface::class, WalletRepository::class);
        $this->app->bind(WalletReadRepositoryInterface::class, WalletReadRepository::class);


        $this->app->bind(WalletWithdrawalRepositoryInterface::class, WalletWithdrawalRepository::class);




        $this->app->bind(WalletTransactionRepositoryInterface::class, WalletTransactionRepository::class);
        $this->app->bind(WalletTransactionReadRepositoryInterface::class, WalletTransactionReadRepository::class);

    }

    public function boot() : void
    {

    }
}
