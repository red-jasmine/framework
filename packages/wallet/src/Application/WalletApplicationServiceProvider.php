<?php

namespace RedJasmine\Wallet\Application;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use RedJasmine\Wallet\Application\Listeners\WalletWithdrawalApprovalListener;
use RedJasmine\Wallet\Application\Listeners\WalletWithdrawalTransferListener;
use RedJasmine\Wallet\Domain\Events\WalletWithdrawal\WalletWithdrawalTransferPrepareEvent;
use RedJasmine\Wallet\Domain\Repositories\WalletRechargeRepositoryInterface;
use RedJasmine\Wallet\Domain\Repositories\WalletRepositoryInterface;
use RedJasmine\Wallet\Domain\Repositories\WalletTransactionRepositoryInterface;
use RedJasmine\Wallet\Domain\Repositories\WalletWithdrawalRepositoryInterface;
use RedJasmine\Wallet\Infrastructure\Repositories\WalletRechargeRepository;
use RedJasmine\Wallet\Infrastructure\Repositories\WalletRepository;
use RedJasmine\Wallet\Infrastructure\Repositories\WalletTransactionRepository;
use RedJasmine\Wallet\Infrastructure\Repositories\WalletWithdrawalRepository;

class WalletApplicationServiceProvider extends ServiceProvider
{
    public function register() : void
    {
        // 统一仓库接口绑定
        $this->app->bind(WalletRepositoryInterface::class, WalletRepository::class);
        $this->app->bind(WalletWithdrawalRepositoryInterface::class, WalletWithdrawalRepository::class);
        $this->app->bind(WalletTransactionRepositoryInterface::class, WalletTransactionRepository::class);
        $this->app->bind(WalletRechargeRepositoryInterface::class, WalletRechargeRepository::class);
    }

    public function boot() : void
    {
        Event::listen([
            WalletWithdrawalTransferPrepareEvent::class,
        ],
            WalletWithdrawalTransferListener::class
        );
    }
}
