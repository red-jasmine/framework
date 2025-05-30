<?php

namespace RedJasmine\Order\Application\Listeners;

use RedJasmine\Order\Application\Services\Refunds\Commands\RefundRejectCommand;
use RedJasmine\Order\Application\Services\Refunds\RefundApplicationService;
use RedJasmine\Order\Domain\Events\AbstractOrderEvent;
use RedJasmine\Order\Domain\Models\Enums\RefundStatusEnum;
use RedJasmine\Order\Domain\Models\Enums\ShippingStatusEnum;

class RefundHandleListener
{
    public function __construct(protected RefundApplicationService $refundCommandService)
    {
    }

    public function handle(AbstractOrderEvent $event) : void
    {
        // 如果商品有存在 退款记录，同时
        $order = $event->order;
        foreach ($order->products as $product) {
            // 如果是刚发货
            if (($product->getOriginal('shipping_status') === ShippingStatusEnum::WAIT_SEND) && $product->last_refund_no) {
                $refund = $this->refundCommandService->repository->findByNo($product->last_refund_no);

                if ($refund->refund_status === RefundStatusEnum::WAIT_SELLER_AGREE) {

                    $command = RefundRejectCommand::from([
                        'refundNo' => $product->last_refund_no,
                        'reason'   => '已发货'

                    ]);
                    $this->refundCommandService->reject($command);
                }
            }


        }


    }
}
