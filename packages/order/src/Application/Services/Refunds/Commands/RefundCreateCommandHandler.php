<?php

namespace RedJasmine\Order\Application\Services\Refunds\Commands;

use Exception;
use RedJasmine\Order\Application\Services\Handlers\Refund\AbstractException;
use RedJasmine\Order\Domain\Models\OrderRefund;
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
            $order = $this->service->orderRepository->find($command->id);
            $order->products;

            $orderProduct = $order->products->where('id', $command->orderProductId)->first();
            $orderRefund  = OrderRefund::make([
                'app_id'    => $order->app_id,
                'seller_id' => $order->seller_id,
                'buyer_id'  => $order->buyer_id,
            ]);
            $orderRefund->setRelation('product', $orderProduct);
            $orderRefund->order_no               = $order->order_no;
            $orderRefund->order_product_id       = $command->orderProductId;
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
