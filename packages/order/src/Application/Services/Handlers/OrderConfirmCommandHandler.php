<?php

namespace RedJasmine\Order\Application\Services\Handlers;

use RedJasmine\Order\Application\UserCases\Commands\OrderConfirmCommand;
use RedJasmine\Order\Domain\Events\OrderConfirmedEvent;


class OrderConfirmCommandHandler extends AbstractOrderCommandHandler
{


    /**
     * @param OrderConfirmCommand $command
     *
     * @return void
     */
    public function handle(OrderConfirmCommand $command) : void
    {

        $order = $this->find($command->id);

        $this->execute(
            execute: fn() => $order->confirm(),
            persistence: fn() => $this->orderRepository->update($order)
        );

        OrderConfirmedEvent::dispatch($order);
    }


}
