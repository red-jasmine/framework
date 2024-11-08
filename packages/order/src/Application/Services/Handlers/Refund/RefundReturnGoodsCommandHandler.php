<?php

namespace RedJasmine\Order\Application\Services\Handlers\Refund;

use Exception;
use RedJasmine\Order\Application\UserCases\Commands\Refund\RefundReturnGoodsCommand;
use RedJasmine\Order\Domain\Models\Enums\EntityTypeEnum;
use RedJasmine\Order\Domain\Models\Enums\Logistics\LogisticsShipperEnum;
use RedJasmine\Order\Domain\Models\OrderLogistics;
use RedJasmine\Support\Exceptions\AbstractException;
use Throwable;

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

        $this->beginDatabaseTransaction();

        try {
            $refund                               = $this->find($command->id);
            $orderLogistics                       = OrderLogistics::newModel();
            $orderLogistics->shipper              = LogisticsShipperEnum::BUYER;
            $orderLogistics->order_product_id     = [ $refund->order_product_id ];
            $orderLogistics->logistics_company_code = $command->logisticsCompanyCode;
            $orderLogistics->logistics_no           = $command->logisticsNo;
            $orderLogistics->status               = $command->status;
            $orderLogistics->shipping_time        = now();


            $refund->returnGoods($orderLogistics);

            $this->refundRepository->update($refund);

            $this->commitDatabaseTransaction();
        } catch (AbstractException $exception) {
            $this->rollBackDatabaseTransaction();
            throw  $exception;
        } catch (Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw  $throwable;
        }


    }

}
