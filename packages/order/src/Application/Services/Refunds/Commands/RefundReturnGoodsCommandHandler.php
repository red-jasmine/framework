<?php

namespace RedJasmine\Order\Application\Services\Refunds\Commands;

use Exception;
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
            $orderLogistics                       = OrderLogistics::make();
            $orderLogistics->shipper              = LogisticsShipperEnum::BUYER;
            $orderLogistics->order_product_id     = [ $refund->order_product_id ];
            $orderLogistics->logistics_company_code = $command->logisticsCompanyCode;
            $orderLogistics->logistics_no           = $command->logisticsNo;
            $orderLogistics->status               = $command->status;
            $orderLogistics->shipping_time        = now();


            $refund->returnGoods($orderLogistics);

            $this->service->repository->update($refund);

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
