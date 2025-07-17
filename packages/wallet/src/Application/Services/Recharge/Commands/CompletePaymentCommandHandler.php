<?php

namespace RedJasmine\Wallet\Application\Services\Recharge\Commands;

use RedJasmine\Support\Application\Commands\CommandHandler;
use RedJasmine\Wallet\Application\Services\Recharge\WalletRechargeApplicationService;
use RedJasmine\Wallet\Domain\Models\Enums\AmountDirectionEnum;
use RedJasmine\Wallet\Domain\Models\Enums\PaymentStatusEnum;
use RedJasmine\Wallet\Domain\Models\Enums\Recharges\RechargeStatusEnum;
use RedJasmine\Wallet\Domain\Models\Enums\TransactionStatusEnum;
use RedJasmine\Wallet\Domain\Models\Enums\TransactionTypeEnum;
use RedJasmine\Wallet\Domain\Models\WalletRecharge;
use RedJasmine\Wallet\Domain\Models\WalletTransaction;
use RedJasmine\Wallet\Domain\Repositories\WalletRepositoryInterface;
use RedJasmine\Wallet\Domain\Repositories\WalletTransactionRepositoryInterface;
use Throwable;

class CompletePaymentCommandHandler extends CommandHandler
{
    public function __construct(
        protected WalletRechargeApplicationService $service,
        protected WalletRepositoryInterface $walletRepository,
        protected WalletTransactionRepositoryInterface $transactionRepository
    ) {
    }

    public function handle(CompletePaymentCommand $command): WalletRecharge
    {
        $this->beginDatabaseTransaction();

        try {
            // 获取充值单
            $recharge = $this->service->repository->find($command->getKey());
            if (!$recharge) {
                throw new \Exception('充值单不存在');
            }

            // 验证充值单状态
            if ($recharge->status !== RechargeStatusEnum::PAYING) {
                throw new \Exception('充值单状态不正确');
            }

            // 验证支付订单号
            if ($recharge->payment_order_no !== $command->paymentOrderNo) {
                throw new \Exception('支付订单号不匹配');
            }

            // 更新充值单状态
            $recharge->status = RechargeStatusEnum::PAID;
            $recharge->payment_status = PaymentStatusEnum::SUCCESS;
            $recharge->platform_order_no = $command->platformOrderNo;
            $recharge->actual_payment_amount = $command->actualPaymentAmount;
            $recharge->paid_at = $command->paidAt;
            $recharge->extra = array_merge($recharge->extra ?? [], [
                'platform_response' => $command->platformResponse
            ], $command->extra);

            // 保存充值单
            $this->service->repository->update($recharge);

            // 获取钱包
            $wallet = $this->walletRepository->find($recharge->wallet_id);
            if (!$wallet) {
                throw new \Exception('钱包不存在');
            }

            // 增加钱包余额
            $wallet->balance = $wallet->balance->add($recharge->amount);
            $this->walletRepository->update($wallet);

            // 创建交易记录
            $transaction = new WalletTransaction();
            $transaction->wallet_id = $wallet->id;
            $transaction->type = TransactionTypeEnum::RECHARGE;
            $transaction->status = TransactionStatusEnum::SUCCESS;
            $transaction->direction = AmountDirectionEnum::INCOME;
            $transaction->amount = $recharge->amount;
            $transaction->currency = $recharge->currency;
            $transaction->related_id = $recharge->id;
            $transaction->related_type = WalletRecharge::class;
            $transaction->description = '钱包充值';
            $transaction->extra = [
                'recharge_id' => $recharge->id,
                'payment_order_no' => $recharge->payment_order_no,
                'platform_order_no' => $recharge->platform_order_no,
            ];
            $transaction->operator_id = $command->operator->getId();
            $transaction->owner_id = $recharge->owner_id;

            $this->transactionRepository->store($transaction);

            // 更新充值单状态为成功
            $recharge->status = RechargeStatusEnum::SUCCESS;
            $this->service->repository->update($recharge);

            $this->commitDatabaseTransaction();
            return $recharge;
        } catch (Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw $throwable;
        }
    }
} 