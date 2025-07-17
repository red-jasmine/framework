<?php

namespace RedJasmine\Wallet\Application\Services\Wallet;


use RedJasmine\Support\Application\ApplicationService;

use RedJasmine\Wallet\Application\Services\Wallet\Commands\WalletCreateCommand;
use RedJasmine\Wallet\Application\Services\Wallet\Commands\WalletCreateCommandHandler;
use RedJasmine\Wallet\Application\Services\Wallet\Commands\WalletTransactionCommand;
use RedJasmine\Wallet\Application\Services\Wallet\Commands\WalletTransactionCommandHandler;
use RedJasmine\Wallet\Application\Services\Wallet\Queries\FindByOwnerTypeQuery;
use RedJasmine\Wallet\Application\Services\Wallet\Queries\FindByOwnerTypeQueryHandler;
use RedJasmine\Wallet\Domain\Models\Wallet;
use RedJasmine\Wallet\Domain\Models\WalletTransaction;
use RedJasmine\Wallet\Domain\Repositories\WalletReadRepositoryInterface;
use RedJasmine\Wallet\Domain\Repositories\WalletRepositoryInterface;
use RedJasmine\Wallet\Domain\Services\WalletService;

/**
 * @method Wallet|null findByOwnerType(FindByOwnerTypeQuery $query)
 * @method Wallet create(WalletCreateCommand $command)
 * @method WalletTransaction  transaction(WalletTransactionCommand $command)
 */
class WalletApplicationService extends ApplicationService
{

    public function __construct(
        public WalletRepositoryInterface $repository,
        public WalletReadRepositoryInterface $readRepository,
        public WalletService $walletService
    ) {
    }

    public static string $hookNamePrefix = 'wallet.application.wallet.command';

    protected static string $modelClass = Wallet::class;


    protected static $macros = [
        'findByOwnerType' => FindByOwnerTypeQueryHandler::class,
        'create'          => WalletCreateCommandHandler::class,
        'transaction'     => WalletTransactionCommandHandler::class,
        'update'          => null,
        'delete'          => null,

    ];


}