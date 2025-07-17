<?php

namespace RedJasmine\Wallet\Application\Services\Recharge\Commands;

use RedJasmine\Support\Application\Commands\CommandHandler;
use RedJasmine\Wallet\Application\Services\Recharge\WalletRechargeApplicationService;
use RedJasmine\Wallet\Domain\Models\Enums\PaymentStatusEnum;
use RedJasmine\Wallet\Domain\Models\Enums\Recharges\RechargeStatusEnum;
use RedJasmine\Wallet\Domain\Models\WalletRecharge;
use Throwable;

class FailPaymentCommandHandler extends CommandHandler
{
    public function __construct(
        protected WalletRechargeApplicationService $service
    ) {
    }

    public function handle(FailPaymentCommand $command): WalletRecharge
    {
        $this->beginDatabaseTransaction();

        try {
            // 获取充值单
            $recharge = $this->service->repository->find($command->rechargeId);
            if (!$recharge) {
                throw new \Exception('充值单不存在');
            }

            // 验证充值单状态
            if (!in_array($recharge->status, [RechargeStatusEnum::CREATED, RechargeStatusEnum::PAYING])) {
                throw new \Exception('充值单状态不正确');
            }

            // 验证支付订单号
            if ($recharge->payment_order_no !== $command->paymentOrderNo) {
                throw new \Exception('支付订单号不匹配');
            }

            // 更新充值单状态
            $recharge->status = RechargeStatusEnum::FAIL;
            $recharge->payment_status = PaymentStatusEnum::FAIL;
            $recharge->platform_order_no = $command->platformOrderNo;
            $recharge->fail_reason = $command->failReason;
            $recharge->fail_code = $command->failCode;
            $recharge->extra = array_merge($recharge->extra ?? [], [
                'platform_response' => $command->platformResponse,
                'fail_reason' => $command->failReason,
                'fail_code' => $command->failCode,
            ], $command->extra);

            // 保存充值单
            $this->service->repository->update($recharge);

            $this->commitDatabaseTransaction();
            return $recharge;
        } catch (Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw $throwable;
        }
    }
} 