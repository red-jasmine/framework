<?php

namespace RedJasmine\Wallet\Application\Services\Recharge;

use RedJasmine\Support\Application\ApplicationService;
use RedJasmine\Wallet\Domain\Models\WalletRecharge;
use RedJasmine\Wallet\Domain\Repositories\WalletRechargeReadRepositoryInterface;
use RedJasmine\Wallet\Domain\Repositories\WalletRechargeRepositoryInterface;
use RedJasmine\Wallet\Domain\Services\WalletRechargeService;

class WalletRechargeApplicationService extends ApplicationService
{

    protected static string $modelClass = WalletRecharge::class;

    public function __construct(
        public WalletRechargeRepositoryInterface $repository,
        public WalletRechargeReadRepositoryInterface $readRepository,
        public WalletRechargeService $rechargeService,
    ) {

    }


    protected static $macros = [
        'create'          => Commands\CreateRechargeCommandHandler::class,
        'initiatePayment' => Commands\InitiatePaymentCommandHandler::class,
        'completePayment' => Commands\CompletePaymentCommandHandler::class,
        'failPayment'     => Commands\FailPaymentCommandHandler::class,
    ];


}