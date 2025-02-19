<?php

namespace RedJasmine\Order\Application\Services\Refunds\Commands;

use Exception;
use RedJasmine\Order\Application\Services\Handlers\Refund\AbstractException;
use RedJasmine\Order\Domain\Models\OrderRefund;
use Throwable;

class RefundCreateCommandHandler extends AbstractRefundCommandHandler
{


    /**
     * @param RefundCreateCommand $command
     *
     * @return int
     * @throws Exception|Throwable
     */
    public function handle(RefundCreateCommand $command) : int
    {
        $this->beginDatabaseTransaction();

        try {
            $order = $this->service->orderRepository->find($command->id);
            $order->products;

            $orderProduct = $order->products->where('id', $command->orderProductId)->first();
            $orderRefund  = OrderRefund::newModel();
            $orderRefund->setRelation('product', $orderProduct);
            $orderRefund->order_id          = $order->id;
            $orderRefund->order_product_id  = $command->orderProductId;
            $orderRefund->refund_type       = $command->refundType;
            $orderRefund->refund_amount     = $command->refundAmount;
            $orderRefund->reason            = $command->reason;
            $orderRefund->info->description = $command->description;
            $orderRefund->info->images      = $command->images;
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
