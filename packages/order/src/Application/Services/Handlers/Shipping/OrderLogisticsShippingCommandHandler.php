<?php

namespace RedJasmine\Order\Application\Services\Handlers\Shipping;

use RedJasmine\Order\Application\Services\Handlers\AbstractOrderCommandHandler;
use RedJasmine\Order\Application\UserCases\Commands\Shipping\OrderLogisticsShippingCommand;
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
            $order                                = $this->find($command->id);
            $orderLogistics                       = OrderLogistics::newModel();
            $orderLogistics->shippable_id         = $order->id;
            $orderLogistics->seller_type          = $order->seller_type;
            $orderLogistics->seller_id            = $order->seller_id;
            $orderLogistics->buyer_type           = $order->buyer_type;
            $orderLogistics->buyer_id             = $order->buyer_id;
            $orderLogistics->shipper              = LogisticsShipperEnum::SELLER;
            $orderLogistics->order_product_id     = $command->orderProducts;
            $orderLogistics->express_company_code = $command->expressCompanyCode;
            $orderLogistics->express_no           = $command->expressNo;
            $orderLogistics->status               = $command->status;
            $orderLogistics->shipping_time        = now();
            $orderLogistics->creator              = $order->updater;

            $this->orderShippingService->logistics($order, $command->isSplit, $orderLogistics);

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
