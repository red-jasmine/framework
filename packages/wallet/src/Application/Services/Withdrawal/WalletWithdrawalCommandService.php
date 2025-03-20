<?php

namespace RedJasmine\Wallet\Application\Services\Withdrawal;

use RedJasmine\Support\Application\ApplicationCommandService;
use RedJasmine\Wallet\Application\Services\Withdrawal\Commands\WalletWithdrawalCreateCommand;
use RedJasmine\Wallet\Application\Services\Withdrawal\Commands\WalletWithdrawalCreateCommandHandler;
use RedJasmine\Wallet\Domain\Models\WalletWithdrawal;
use RedJasmine\Wallet\Domain\Repositories\WalletWithdrawalRepositoryInterface;

/**
 * @method WalletWithdrawal create(WalletWithdrawalCreateCommand $command)
 */
class WalletWithdrawalCommandService extends ApplicationCommandService
{
    public static string $hookNamePrefix = 'wallet.application.withdrawal.command';

    public function __construct(
        public WalletWithdrawalRepositoryInterface $repository

    ) {
    }

    protected static string $modelClass = WalletWithdrawal::class;


    protected static $macros = [
        'create' => WalletWithdrawalCreateCommandHandler::class,
    ];

}