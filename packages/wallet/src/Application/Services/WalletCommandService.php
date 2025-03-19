<?php

namespace RedJasmine\Wallet\Application\Services;

use RedJasmine\Support\Application\ApplicationCommandService;
use RedJasmine\Support\Application\CommandHandlers\CreateCommandHandler;
use RedJasmine\Support\Data\Data;
use RedJasmine\Wallet\Application\Services\Commands\WalletCreateCommand;
use RedJasmine\Wallet\Application\Services\Commands\WalletCreateCommandHandler;
use RedJasmine\Wallet\Application\Services\Commands\WalletTransactionCommand;
use RedJasmine\Wallet\Application\Services\Commands\WalletTransactionCommandHandler;
use RedJasmine\Wallet\Domain\Models\Wallet;
use RedJasmine\Wallet\Domain\Repositories\WalletRepositoryInterface;

/**
 * @method Wallet create(WalletCreateCommand $command)
 * @method  transaction(WalletTransactionCommand $command)
 */
class WalletCommandService extends ApplicationCommandService
{

    public function __construct(
        public WalletRepositoryInterface $repository
    ) {
    }

    public static string $hookNamePrefix = 'wallet.application.wallet.command';

    protected static string $modelClass = Wallet::class;


    protected static $macros = [
        'create'      => CreateCommandHandler::class,
        'transaction' => WalletTransactionCommandHandler::class
    ];


}