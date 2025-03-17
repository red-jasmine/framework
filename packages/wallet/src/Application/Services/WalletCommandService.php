<?php

namespace RedJasmine\Wallet\Application\Services;

use RedJasmine\Support\Application\ApplicationCommandService;
use RedJasmine\Wallet\Application\Services\Commands\WalletCreateCommandHandler;
use RedJasmine\Wallet\Domain\Models\Wallet;
use RedJasmine\Wallet\Domain\Repositories\WalletRepositoryInterface;

class WalletCommandService extends ApplicationCommandService
{

    public function __construct(
        public WalletRepositoryInterface $repository
    ) {
    }

    protected static string $modelClass = Wallet::class;


    protected static $macros = [
        'create' => WalletCreateCommandHandler::class,
    ];
}