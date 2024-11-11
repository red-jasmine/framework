<?php

namespace RedJasmine\Order\Application\Services\Handlers\Others;

use RedJasmine\Order\Application\Services\Handlers\AbstractOrderCommandHandler;
use RedJasmine\Order\Application\UserCases\Commands\Others\OrderStarCommand;
use RedJasmine\Support\Exceptions\AbstractException;

class OrderStarCommandHandler extends AbstractOrderCommandHandler
{

    /**
     * @param OrderStarCommand $command
     * @return void
     * @throws AbstractException
     */
    public function handle(OrderStarCommand $command) : void
    {
        $this->beginDatabaseTransaction();

        try {
            $order = $this->find($command->id);


            $order->star($command->star);

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
