<?php

namespace RedJasmine\Wallet\Application;

use Illuminate\Support\ServiceProvider;
use RedJasmine\Support\Facades\Hook;
use RedJasmine\Wallet\Domain\Repositories\WalletRepositoryInterface;
use RedJasmine\Wallet\Domain\Repositories\WalletWithdrawalRepositoryInterface;
use RedJasmine\Wallet\Infrastructure\Repositories\Eloquent\WalletRepository;
use RedJasmine\Wallet\Infrastructure\Repositories\Eloquent\WalletWithdrawalRepository;

class WalletApplicationServiceProvider extends ServiceProvider
{
    public function register() : void
    {
        $this->app->bind(WalletRepositoryInterface::class, WalletRepository::class);
        $this->app->bind(WalletWithdrawalRepositoryInterface::class, WalletWithdrawalRepository::class);


    }

    public function boot() : void
    {

    }
}
