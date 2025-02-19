<?php

namespace RedJasmine\Order\Application\Services\Handlers\Others;

use RedJasmine\Order\Application\Services\Handlers\AbstractOrderCommandHandler;
use RedJasmine\Order\Application\UserCases\Commands\Others\OrderSellerCustomStatusCommand;
use RedJasmine\Support\Exceptions\AbstractException;
use Throwable;

class OrderSellerCustomStatusCommandHandler extends AbstractOrderCommandHandler
{


    /**
     * @param OrderSellerCustomStatusCommand $command
     * @return void
     * @throws AbstractException
     * @throws Throwable
     */
    public function handle(OrderSellerCustomStatusCommand $command) : void
    {
        $this->beginDatabaseTransaction();

        try {
            $order = $this->find($command->id);


            $order->setSellerCustomStatus($command->sellerCustomStatus, $command->orderProductId);

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
