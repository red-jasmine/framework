<?php

namespace RedJasmine\Wallet\Application\Services\Withdrawal;

use RedJasmine\Support\Application\ApplicationService;
use RedJasmine\Wallet\Application\Services\Withdrawal\Commands\WalletWithdrawalApprovalCommand;
use RedJasmine\Wallet\Application\Services\Withdrawal\Commands\WalletWithdrawalApprovalCommandHandler;
use RedJasmine\Wallet\Application\Services\Withdrawal\Commands\WalletWithdrawalCreateCommand;
use RedJasmine\Wallet\Application\Services\Withdrawal\Commands\WalletWithdrawalCreateCommandHandler;
use RedJasmine\Wallet\Application\Services\Withdrawal\Commands\WalletWithdrawalTransferCallbackCommand;
use RedJasmine\Wallet\Application\Services\Withdrawal\Commands\WalletWithdrawalTransferCallbackCommandHandler;
use RedJasmine\Wallet\Application\Services\Withdrawal\Commands\WalletWithdrawalTransferPrepareCommand;
use RedJasmine\Wallet\Application\Services\Withdrawal\Commands\WalletWithdrawalTransferPrepareCommandHandler;
use RedJasmine\Wallet\Domain\Models\WalletWithdrawal;
use RedJasmine\Wallet\Domain\Repositories\WalletWithdrawalRepositoryInterface;

/**
 * @method WalletWithdrawal create(WalletWithdrawalCreateCommand $command)
 * @method bool approval(WalletWithdrawalApprovalCommand $command)
 * @method bool transferCallback(WalletWithdrawalTransferCallbackCommand $command)
 * @method bool transferPrepare(WalletWithdrawalTransferPrepareCommand $command)
 */
class WalletWithdrawalApplicationService extends ApplicationService
{
    public static string $hookNamePrefix = 'wallet.application.withdrawal';

    public function __construct(
        public WalletWithdrawalRepositoryInterface $repository,

    ) {
    }

    protected static string $modelClass = WalletWithdrawal::class;


    protected static $macros = [
        'create'           => WalletWithdrawalCreateCommandHandler::class,
        'approval'         => WalletWithdrawalApprovalCommandHandler::class,
        'transferCallback' => WalletWithdrawalTransferCallbackCommandHandler::class,
        'transferPrepare'  => WalletWithdrawalTransferPrepareCommandHandler::class,
        'update'           => null,
        'delete'           => null,
    ];

}
