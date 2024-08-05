<?php

namespace RedJasmine\Order\Application\Services\Handlers\Shipping;

use RedJasmine\Order\Application\Services\Handlers\AbstractOrderCommandHandler;
use RedJasmine\Order\Application\UserCases\Commands\Shipping\OrderShippingCardKeyCommand;
use RedJasmine\Order\Domain\OrderFactory;
use RedJasmine\Order\Domain\Repositories\OrderRepositoryInterface;
use RedJasmine\Order\Domain\Services\OrderShippingService;

class OrderShippingCardKeyCommandHandler extends AbstractOrderCommandHandler
{

    public function __construct(
        protected OrderRepositoryInterface $orderRepository,
        protected OrderShippingService     $orderShippingService
    )
    {

        parent::__construct($orderRepository);
    }


    public function handle(OrderShippingCardKeyCommand $command) : void
    {

        $order = $this->find($command->id);

        $orderProductCardKey = app(OrderFactory::class)->createOrderProductCardKey();

        $orderProductCardKey->order_product_id = $command->orderProductId;
        $orderProductCardKey->content          = $command->content;
        $orderProductCardKey->num              = $command->num;
        $orderProductCardKey->status           = $command->status;
        $orderProductCardKey->creator          = $order->updater;


        $this->execute(
            execute: fn() => $this->orderShippingService->cardKey($order, $orderProductCardKey),
            persistence: fn() => $this->orderRepository->update($order)
        );

    }


}
