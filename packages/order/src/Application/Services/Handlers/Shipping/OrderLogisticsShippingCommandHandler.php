<?php

namespace RedJasmine\Order\Application\Services\Handlers\Shipping;

use RedJasmine\Order\Application\Services\Handlers\AbstractOrderCommandHandler;
use RedJasmine\Order\Application\UserCases\Commands\Shipping\OrderLogisticsShippingCommand;
use RedJasmine\Order\Domain\Models\Enums\EntityTypeEnum;
use RedJasmine\Order\Domain\Models\Enums\Logistics\LogisticsShipperEnum;
use RedJasmine\Order\Domain\Models\OrderLogistics;
use RedJasmine\Order\Domain\Repositories\OrderRepositoryInterface;
use RedJasmine\Order\Domain\Services\OrderShippingService;
use RedJasmine\Support\Exceptions\AbstractException;
use Throwable;

class OrderLogisticsShippingCommandHandler extends AbstractOrderCommandHandler
{

    public function __construct(
        protected OrderRepositoryInterface $orderRepository,
        protected OrderShippingService     $orderShippingService
    )
    {
        parent::__construct($orderRepository);
    }


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
            $this->orderShippingService->logistics($order, $command->isSplit, $orderLogistics, $command->isFinished);

            $this->orderRepository->update($order);

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
