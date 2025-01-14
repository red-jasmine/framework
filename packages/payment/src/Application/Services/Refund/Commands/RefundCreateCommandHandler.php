<?php

namespace RedJasmine\Payment\Application\Services\Refund\Commands;

use RedJasmine\Payment\Application\Services\Refund\RefundCommandService;
use RedJasmine\Payment\Domain\Models\Refund;
use RedJasmine\Support\Application\CommandHandler;
use RedJasmine\Support\Exceptions\AbstractException;
use Throwable;

class RefundCreateCommandHandler extends CommandHandler
{
    public function __construct(protected RefundCommandService $service)
    {
    }


    /**
     * @param  RefundCreateCommand  $command
     *
     * @return Refund
     * @throws AbstractException
     * @throws Throwable
     */
    public function handle(RefundCreateCommand $command) : Refund
    {
        // TODO 加锁
        // 每笔订单退款 需要间隔 1 分钟
        $this->beginDatabaseTransaction();

        try {
            $trade = $this->service->tradeRepository->findByNo($command->tradeNo);

            $refund = Refund::make();

            $refund->merchant_refund_no          = $command->merchantRefundNo;
            $refund->merchant_refund_order_no    = $command->merchantRefundOrderNo;
            $refund->refund_reason               = $command->refundReason;
            $refund->refundAmount                = $command->refundAmount;
            $refund->extension->notify_url       = $command->notifyUrl;
            $refund->extension->pass_back_params = $command->passBackParams;
            $refund->setGoodsDetails($command->goodDetails);

            $trade->createRefund($refund);

            if ($command->isAutoExecute) {
                $refund->executing();
            }
            $this->service->tradeRepository->update($trade);

            $this->commitDatabaseTransaction();

        } catch (AbstractException $exception) {
            $this->rollBackDatabaseTransaction();
            throw  $exception;
        } catch (Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw  $throwable;
        }


        return $refund;
    }
}
