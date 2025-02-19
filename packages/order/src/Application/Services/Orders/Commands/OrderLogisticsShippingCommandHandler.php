<?php

namespace RedJasmine\Order\Application\Services\Orders\Commands;

use RedJasmine\Order\Domain\Models\Enums\Logistics\LogisticsShipperEnum;
use RedJasmine\Order\Domain\Models\OrderLogistics;
use RedJasmine\Support\Exceptions\AbstractException;
use Throwable;

class OrderLogisticsShippingCommandHandler extends AbstractOrderCommandHandler
{


    public function handle(OrderLogisticsShippingCommand $command) : void
    {

        $this->beginDatabaseTransaction();

        try {
            $order          = $this->find($command->id);
            $orderLogistics = OrderLogistics::newModel();

            $orderLogistics->shipper                = LogisticsShipperEnum::SELLER;
            $orderLogistics->order_product_id       = $command->orderProducts;
            $orderLogistics->logistics_company_code = $command->logisticsCompanyCode;
            $orderLogistics->logistics_no           = $command->logisticsNo;
            $orderLogistics->status                 = $command->status;
            $orderLogistics->shipping_time          = now();
            $this->service->orderShippingService
                ->logistics($order, $command->isSplit, $orderLogistics,
                    $command->isFinished);

            $this->service->repository->update($order);

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
