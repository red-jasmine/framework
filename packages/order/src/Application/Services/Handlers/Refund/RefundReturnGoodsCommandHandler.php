<?php

namespace RedJasmine\Order\Application\Services\Handlers\Refund;

use Exception;
use RedJasmine\Order\Application\UserCases\Commands\Refund\RefundReturnGoodsCommand;
use RedJasmine\Order\Domain\Models\Enums\Logistics\LogisticsShippableTypeEnum;
use RedJasmine\Order\Domain\Models\Enums\Logistics\LogisticsShipperEnum;
use RedJasmine\Order\Domain\Models\OrderLogistics;

class RefundReturnGoodsCommandHandler extends AbstractRefundCommandHandler
{


    /**
     * @param RefundReturnGoodsCommand $command
     *
     * @return void
     * @throws Exception
     */
    public function handle(RefundReturnGoodsCommand $command) : void
    {
        $refund = $this->find($command->rid);

        $orderLogistics                       = OrderLogistics::newModel();
        $orderLogistics->shippable_type       = LogisticsShippableTypeEnum::REFUND;
        $orderLogistics->shippable_id         = $refund->id;
        $orderLogistics->seller               = $refund->seller;
        $orderLogistics->buyer                = $refund->buyer;
        $orderLogistics->shipper              = LogisticsShipperEnum::BUYER;
        $orderLogistics->order_product_id     = [ $refund->order_product_id ];
        $orderLogistics->express_company_code = $command->expressCompanyCode;
        $orderLogistics->express_no           = $command->expressNo;
        $orderLogistics->status               = $command->status;
        $orderLogistics->shipping_time        = now();
        $orderLogistics->creator              = $refund->updater;

        $this->execute(
            execute: fn() => $refund->returnGoods($orderLogistics),
            persistence: fn() => $this->refundRepository->update($refund),
        );


    }

}
