<?php

namespace RedJasmine\Wallet\Application\Services\Recharge\Commands;

use RedJasmine\Support\Application\Commands\CommandHandler;
use RedJasmine\Wallet\Application\Services\Recharge\WalletRechargeApplicationService;
use RedJasmine\Wallet\Application\Services\Wallet\Commands\WalletTransactionCommand;
use RedJasmine\Wallet\Application\Services\Wallet\WalletApplicationService;
use RedJasmine\Wallet\Domain\Models\Enums\AmountDirectionEnum;
use RedJasmine\Wallet\Domain\Models\Enums\TransactionTypeEnum;
use RedJasmine\Wallet\Domain\Models\WalletRecharge;
use RedJasmine\Wallet\Exceptions\WalletRechargeException;
use Throwable;

class CompletePaymentCommandHandler extends CommandHandler
{
    public function __construct(
        protected WalletRechargeApplicationService $service,
        protected WalletApplicationService $walletApplicationService,
    ) {
    }

    /**
     * @param  CompletePaymentCommand  $command
     *
     * @return WalletRecharge
     * @throws Throwable
     * @throws WalletRechargeException
     */
    public function handle(CompletePaymentCommand $command) : WalletRecharge
    {
        $this->beginDatabaseTransaction();

        try {
            // 获取充值单
            /**
             * @var WalletRecharge $recharge
             */
            $recharge = $this->service->repository->findByNo($command->getKey());

            $recharge->paid($command);
            // 保存充值单
            $walletTransactionCommand = new WalletTransactionCommand();
            $walletTransactionCommand->setKey($recharge->wallet_id);
            $walletTransactionCommand->amount          = $recharge->amount;
            $walletTransactionCommand->direction       = AmountDirectionEnum::INCOME;
            $walletTransactionCommand->title           = '充值';
            $walletTransactionCommand->description     = '充值';
            $walletTransactionCommand->transactionType = TransactionTypeEnum::RECHARGE;
            $walletTransactionCommand->outTradeNo      = $recharge->no;

            $this->walletApplicationService->transaction($walletTransactionCommand);

            $recharge->success();

            $this->service->repository->update($recharge);
            $this->commitDatabaseTransaction();
            return $recharge;
        } catch (Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw $throwable;
        }
    }
}