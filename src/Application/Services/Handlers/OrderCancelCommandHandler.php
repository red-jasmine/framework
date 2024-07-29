<?php

namespace RedJasmine\Order\Application\Services\Handlers;

use RedJasmine\Order\Application\UserCases\Commands\OrderCancelCommand;

class OrderCancelCommandHandler extends AbstractOrderCommandHandler
{

    public function handle(OrderCancelCommand $command) : void
    {

        $order = $this->find($command->id);

        $this->execute(
            execute: fn() => $order->cancel($command->cancelReason),
            persistence: fn() => $this->orderRepository->update($order)
        );

    }

}
