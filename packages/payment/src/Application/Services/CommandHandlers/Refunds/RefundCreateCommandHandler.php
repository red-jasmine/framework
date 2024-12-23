<?php

namespace RedJasmine\Payment\Application\Services\CommandHandlers\Refunds;

use RedJasmine\Payment\Application\Commands\Refund\RefundCreateCommand;
use RedJasmine\Payment\Domain\Models\Refund;
use RedJasmine\Payment\Domain\Repositories\TradeRepositoryInterface;
use RedJasmine\Support\Application\CommandHandler;
use RedJasmine\Support\Exceptions\AbstractException;
use Throwable;

class RefundCreateCommandHandler extends CommandHandler
{

    public function __construct(
        protected TradeRepositoryInterface $tradeRepository
    ) {
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
            $trade = $this->tradeRepository->findByNo($command->tradeNo);

            $refund = Refund::make();

            $refund->merchant_refund_no          = $command->merchantRefundNo;
            $refund->merchant_refund_order_no    = $command->merchantRefundOrderNo;
            $refund->refund_reason               = $command->refundReason;
            $refund->refundAmount                = $command->refundAmount;
            $refund->extension->notify_url       = $command->notifyUrl;
            $refund->extension->pass_back_params = $command->passBackParams;
            $refund->setGoodsDetails($command->goodDetails);

            $trade->createRefund($refund);

            $this->tradeRepository->update($refund);

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
