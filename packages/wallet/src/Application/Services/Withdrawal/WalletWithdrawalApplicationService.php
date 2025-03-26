<?php

namespace RedJasmine\Wallet\Application\Services\Withdrawal;

use RedJasmine\Support\Application\ApplicationCommandService;
use RedJasmine\Wallet\Application\Services\Withdrawal\Commands\WalletWithdrawalApprovalCommand;
use RedJasmine\Wallet\Application\Services\Withdrawal\Commands\WalletWithdrawalApprovalCommandHandler;
use RedJasmine\Wallet\Application\Services\Withdrawal\Commands\WalletWithdrawalCreateCommand;
use RedJasmine\Wallet\Application\Services\Withdrawal\Commands\WalletWithdrawalCreateCommandHandler;
use RedJasmine\Wallet\Application\Services\Withdrawal\Commands\WalletWithdrawalPaymentCommand;
use RedJasmine\Wallet\Application\Services\Withdrawal\Commands\WalletWithdrawalPaymentCommandHandler;
use RedJasmine\Wallet\Domain\Models\WalletWithdrawal;
use RedJasmine\Wallet\Domain\Repositories\WalletWithdrawalRepositoryInterface;

/**
 * @method WalletWithdrawal create(WalletWithdrawalCreateCommand $command)
 * @method bool approval(WalletWithdrawalApprovalCommand $command)
 * @method bool payment(WalletWithdrawalPaymentCommand $command)
 */
class WalletWithdrawalApplicationService extends ApplicationCommandService
{
    public static string $hookNamePrefix = 'wallet.application.withdrawal.command';

    public function __construct(
        public WalletWithdrawalRepositoryInterface $repository

    ) {
    }

    protected static string $modelClass = WalletWithdrawal::class;


    protected static $macros = [
        'create'   => WalletWithdrawalCreateCommandHandler::class,
        'update'   => null,
        'delete'   => null,
        'approval' => WalletWithdrawalApprovalCommandHandler::class,
        'payment'  => WalletWithdrawalPaymentCommandHandler::class,
    ];

}