<?php

namespace RedJasmine\Order\Application\Listeners;

use RedJasmine\Order\Application\Services\RefundCommandService;
use RedJasmine\Order\Application\UserCases\Commands\Refund\RefundRejectCommand;
use RedJasmine\Order\Domain\Events\AbstractOrderEvent;
use RedJasmine\Order\Domain\Models\Enums\RefundStatusEnum;
use RedJasmine\Order\Domain\Models\Enums\ShippingStatusEnum;

class RefundHandleListener
{
    public function __construct(protected RefundCommandService $refundCommandService)
    {
    }

    public function handle(AbstractOrderEvent $event) : void
    {
        // 如果商品有存在 退款记录，同时
        $order = $event->order;
        foreach ($order->products as $product) {


            // 如果是刚发货
            if ($product->getOriginal('shipping_status') === ShippingStatusEnum::WAIT_SEND) {

                // 对于之前的 售后 那么就直接拒绝
                if ($product->refund_status === RefundStatusEnum::WAIT_SELLER_AGREE) {

                    $command = RefundRejectCommand::from([
                                                             'id'     => $product->refund_id,
                                                             'reason' => '已发货'

                                                         ]);
                    $this->refundCommandService->reject($command);
                }


            }


        }


    }
}
