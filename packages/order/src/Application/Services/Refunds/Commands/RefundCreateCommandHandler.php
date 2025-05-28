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
     * @return int
     * @throws Exception|Throwable
     */
    public function handle(RefundCreateCommand $command) : int
    {
        $this->beginDatabaseTransaction();

        try {
            $order = $this->service->orderRepository->findByNo($command->orderNo);
            $order->products;
            $orderProduct = $order->products->where('order_product_no', $command->orderProductNo)->firstOrFail();
            // 创建退款单实例
            $orderRefund = $order->newRefundInstance($orderProduct);

            $orderRefund->refund_type            = $command->refundType;
            $orderRefund->refund_amount          = $command->refundAmount;
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

        return $orderRefund->id;
    }

}
