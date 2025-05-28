<?php

namespace RedJasmine\Order\Application\Services\Refunds\Commands;

use Exception;
use RedJasmine\Order\Application\Services\Handlers\Refund\AbstractException;
use RedJasmine\Order\Domain\Models\Refund;
use Throwable;

class RefundCreateCommandHandler extends AbstractRefundCommandHandler
{


    /**
     * @param  RefundCreateCommand  $command
     *
     * @return string
     * @throws Exception|Throwable
     */
    public function handle(RefundCreateCommand $command) : string
    {
        $this->beginDatabaseTransaction();

        try {
            $order = $this->service->orderRepository->findByNo($command->orderNo);
            $order->products;
            $orderProduct = $order->products->where('order_product_no', $command->orderProductNo)->firstOrFail();
            // 创建退款单实例
            $orderRefund = $order->newRefundInstance($orderProduct);

            $orderRefund->refund_type           = $command->refundType;
            $orderRefund->refund_product_amount = $command->refundProductAmount;
            $orderRefund->refund_freight_amount = $command->refundFreightAmount;

            $orderRefund->reason                 = $command->reason;
            $orderRefund->extension->description = $command->description;
            $orderRefund->extension->images      = $command->images;


            $order->createRefund($orderRefund);

            $this->service->orderRepository->store($order);

            $this->commitDatabaseTransaction();
        } catch (AbstractException $exception) {
            $this->rollBackDatabaseTransaction();
            throw  $exception;
        } catch (Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw  $throwable;
        }

        return $orderRefund->refund_no;
    }

}
