<?php

namespace RedJasmine\Wallet\Application\Services\Recharge\Commands;

use RedJasmine\Support\Application\Commands\CommandHandler;
use RedJasmine\Wallet\Application\Services\Recharge\WalletRechargeApplicationService;
use RedJasmine\Wallet\Domain\Models\Enums\PaymentStatusEnum;
use RedJasmine\Wallet\Domain\Models\Enums\Recharges\RechargeStatusEnum;
use RedJasmine\Wallet\Domain\Models\WalletRecharge;
use Throwable;

class InitiatePaymentCommandHandler extends CommandHandler
{
    public function __construct(
        protected WalletRechargeApplicationService $service
    ) {
    }

    public function handle(InitiatePaymentCommand $command): WalletRecharge
    {
        $this->beginDatabaseTransaction();

        try {
            // 获取充值单
            $recharge = $this->service->repository->find($command->rechargeId);
            if (!$recharge) {
                throw new \Exception('充值单不存在');
            }

            // 验证充值单状态
            if ($recharge->status !== RechargeStatusEnum::CREATED) {
                throw new \Exception('充值单状态不正确');
            }

            // 更新充值单状态
            $recharge->status = RechargeStatusEnum::PAYING;
            $recharge->payment_status = PaymentStatusEnum::PAYING;
            $recharge->payment_order_no = $command->paymentOrderNo;
            $recharge->platform_order_no = $command->platformOrderNo;
            $recharge->payment_url = $command->paymentUrl;
            $recharge->payment_qr_code = $command->paymentQrCode;
            $recharge->extra = array_merge($recharge->extra ?? [], $command->extra);

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