<?php

namespace RedJasmine\Wallet\Application\Services\Recharge\Commands;

use Exception;
use RedJasmine\Support\Application\Commands\CommandHandler;
use RedJasmine\Wallet\Application\Services\Recharge\WalletRechargeApplicationService;
use RedJasmine\Wallet\Domain\Data\Payment\PaymentTradeData;
use RedJasmine\Wallet\Domain\Repositories\WalletRepositoryInterface;
use RedJasmine\Wallet\Exceptions\WalletException;
use Throwable;

class CreateRechargeCommandHandler extends CommandHandler
{
    public function __construct(
        protected WalletRechargeApplicationService $service,
        protected WalletRepositoryInterface $walletRepository,

    ) {
    }

    /**
     * @param  CreateRechargeCommand  $command
     *
     * @return PaymentTradeData
     * @throws Throwable
     * @throws WalletException
     */
    public function handle(CreateRechargeCommand $command) : PaymentTradeData
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

            // 调用创建支付单
            $paymentTradeData = $this->service->rechargeService->createPayment($recharge);

            $this->service->repository->update($recharge);

            $this->commitDatabaseTransaction();

            return $paymentTradeData;
        } catch (Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw $throwable;
        }
    }
}