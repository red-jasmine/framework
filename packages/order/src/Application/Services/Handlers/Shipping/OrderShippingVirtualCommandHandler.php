<?php

namespace RedJasmine\Order\Application\Services\Handlers\Shipping;

use RedJasmine\Order\Application\Services\Handlers\AbstractOrderCommandHandler;
use RedJasmine\Order\Application\UserCases\Commands\Shipping\OrderShippingVirtualCommand;
use RedJasmine\Order\Domain\Repositories\OrderRepositoryInterface;
use RedJasmine\Order\Domain\Services\OrderShippingService;

class OrderShippingVirtualCommandHandler extends AbstractOrderCommandHandler
{

    public function __construct(
        protected OrderRepositoryInterface $orderRepository,
        protected OrderShippingService     $orderShippingService
    )
    {

        parent::__construct($orderRepository);
    }


    public function handle(OrderShippingVirtualCommand $command) : void
    {

        $order = $this->find($command->id);

        $this->execute(
            execute: fn() => $this->orderShippingService->virtual($order, $command->orderProductId, $command->isFinished),
            persistence: fn() => $this->orderRepository->update($order)
        );

    }


}
