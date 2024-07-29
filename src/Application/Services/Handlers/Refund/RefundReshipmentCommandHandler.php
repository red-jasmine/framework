<?php

namespace RedJasmine\Order\Application\Services\Handlers\Refund;

use Exception;
use RedJasmine\Order\Application\UserCases\Commands\Refund\RefundReshipmentCommand;
use RedJasmine\Order\Domain\Models\Enums\Logistics\LogisticsShipperEnum;
use RedJasmine\Order\Domain\OrderFactory;

class RefundReshipmentCommandHandler extends AbstractRefundCommandHandler
{


    /**
     * @param RefundReshipmentCommand $command
     *
     * @return void
     * @throws Exception
     */
    public function handle(RefundReshipmentCommand $command) : void
    {

        $refund                               = $this->find($command->rid);
        $orderLogistics                       = app(OrderFactory::class)->createOrderLogistics();
        $orderLogistics->shippable_type       = 'refund';
        $orderLogistics->shippable_id         = $refund->id;
        $orderLogistics->seller               = $refund->seller;
        $orderLogistics->buyer                = $refund->buyer;
        $orderLogistics->shipper              = LogisticsShipperEnum::SELLER;
        $orderLogistics->order_product_id     = [ $refund->order_product_id ];
        $orderLogistics->express_company_code = $command->expressCompanyCode;
        $orderLogistics->express_no           = $command->expressNo;
        $orderLogistics->status               = $command->status;
        $orderLogistics->shipping_time        = now();
        $orderLogistics->creator              = $refund->updater;

        $this->execute(
            execute: fn() => $refund->reshipment($orderLogistics),
            persistence: fn() => $this->refundRepository->update($refund)
        );


    }

}
