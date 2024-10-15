<?php

namespace RedJasmine\Order\Application\Services\Handlers\Refund;

use Exception;
use RedJasmine\Order\Application\Services\Handlers\AbstractOrderCommandHandler;
use RedJasmine\Order\Application\UserCases\Commands\Refund\RefundCreateCommand;
use RedJasmine\Order\Domain\OrderFactory;

class RefundCreateCommandHandler extends AbstractOrderCommandHandler
{

    /**
     * @param RefundCreateCommand $command
     *
     * @return int
     * @throws Exception
     */
    public function handle(RefundCreateCommand $command) : int
    {
        $order                         = $this->find($command->id);
        $orderRefund                   = app(OrderFactory::class)->createRefund($order);
        $orderRefund->order_product_id = $command->orderProductId;
        $orderRefund->refund_type      = $command->refundType;
        $orderRefund->refund_amount    = $command->refundAmount;
        $orderRefund->description      = $command->description;
        $orderRefund->images           = $command->images;
        $orderRefund->reason           = $command->reason;
        $orderRefund->creator          =  $order->updater;
        $this->execute(
            execute: fn() => $order->createRefund($orderRefund),
            persistence: fn() => $this->orderRepository->update($order),
        );
        return $orderRefund->id;
    }

}
