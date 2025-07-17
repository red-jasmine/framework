<?php

namespace RedJasmine\Wallet\Application\Services\Recharge\Commands;

use Exception;
use RedJasmine\Support\Application\Commands\CommandHandler;
use RedJasmine\Wallet\Application\Services\Recharge\WalletRechargeApplicationService;
use RedJasmine\Wallet\Domain\Models\Enums\PaymentStatusEnum;
use RedJasmine\Wallet\Domain\Models\Enums\Recharges\RechargeStatusEnum;
use RedJasmine\Wallet\Domain\Models\WalletRecharge;
use RedJasmine\Wallet\Domain\Repositories\WalletRepositoryInterface;

use Throwable;

class CreateRechargeCommandHandler extends CommandHandler
{
    public function __construct(
        protected WalletRechargeApplicationService $service,
        protected WalletRepositoryInterface $walletRepository,

    ) {
    }

    public function handle(CreateRechargeCommand $command) : WalletRecharge
    {
        $this->beginDatabaseTransaction();

        try {
            // 验证钱包是否存在
            $wallet = $this->walletRepository->find($command->getKey());
            if (!$wallet) {
                throw new Exception('钱包不存在');
            }

            $recharge = $this->service->rechargeService->create($wallet, $command);



            // 保存充值单
            $this->service->repository->store($recharge);

            $this->commitDatabaseTransaction();
            return $recharge;
        } catch (Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw $throwable;
        }
    }
}