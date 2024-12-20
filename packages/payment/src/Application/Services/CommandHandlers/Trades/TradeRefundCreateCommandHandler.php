<?php

namespace RedJasmine\Payment\Application\Services\CommandHandlers\Trades;

use RedJasmine\Payment\Application\Commands\Trade\TradeRefundCreateCommand;
use RedJasmine\Payment\Domain\Models\Refund;
use RedJasmine\Payment\Domain\Repositories\TradeRepositoryInterface;
use RedJasmine\Support\Application\CommandHandler;
use RedJasmine\Support\Exceptions\AbstractException;
use Throwable;

class TradeRefundCreateCommandHandler extends CommandHandler
{

    public function __construct(
        protected TradeRepositoryInterface $tradeRepository
    )
    {
    }

    /**
     * @param TradeRefundCreateCommand $command
     * @return void
     * @throws AbstractException
     * @throws Throwable
     */
    public function handle(TradeRefundCreateCommand $command) : void
    {
        // TODO 加锁
        // 每笔订单退款 需要建个 1 分钟
        $this->beginDatabaseTransaction();

        try {
            $trade = $this->tradeRepository->find($command->tradeId);

            $refund = new  Refund();

            $refund->merchant_refund_no       = $command->merchantRefundNo;
            $refund->merchant_refund_order_no = $command->merchantRefundOrderNo;
            $refund->refund_reason            = $command->refundSeason;
            $refund->refundAmount             = $command->refundAmount;
            $refund->setGoodsDetails($command->goodDetails);

            $trade->createRefund($refund);
            $this->tradeRepository->update($refund);


            $this->commitDatabaseTransaction();
            // 触发 事件 异步申请退款 还是同步申请退款？
            // 异步退款 提示系统 能力
        } catch (AbstractException $exception) {
            $this->rollBackDatabaseTransaction();
            throw  $exception;
        } catch (Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw  $throwable;
        }
        // 返回处理中、等待异步处理
    }
}
