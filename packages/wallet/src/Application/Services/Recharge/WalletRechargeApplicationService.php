<?php

namespace RedJasmine\Wallet\Application\Services\Recharge;

use RedJasmine\Support\Application\ApplicationService;
use RedJasmine\Wallet\Domain\Models\WalletRecharge;
use RedJasmine\Wallet\Domain\Repositories\WalletRechargeReadRepositoryInterface;
use RedJasmine\Wallet\Domain\Repositories\WalletRechargeRepositoryInterface;

class WalletRechargeApplicationService extends ApplicationService
{

    protected static string $modelClass = WalletRecharge::class;

    public function __construct(
        public WalletRechargeRepositoryInterface $repository,
        public WalletRechargeReadRepositoryInterface $readRepository,
    ) {

    }


    protected static $macros = [
        'create' => null,
        'update' => null,
        'delete' => null,
    ];


}