<?php

namespace RedJasmine\Order\Application\Services\Handlers\Others;

use RedJasmine\Order\Application\Services\Handlers\AbstractOrderCommandHandler;
use RedJasmine\Order\Application\UserCases\Commands\Others\OrderSellerCustomStatusCommand;

class OrderSellerCustomStatusCommandHandler extends AbstractOrderCommandHandler
{




    public function handle(OrderSellerCustomStatusCommand $command) : void
    {

        $order = $this->find($command->id);
        $this->pipelineManager()->call('executing');
        $this->pipelineManager()->call('execute', fn() => $order->setSellerCustomStatus($command->sellerCustomStatus, $command->orderProductId));
        $this->orderRepository->update($order);
        $this->pipelineManager()->call('executed');
    }
}
