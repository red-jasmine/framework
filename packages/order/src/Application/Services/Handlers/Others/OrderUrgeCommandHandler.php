<?php

namespace RedJasmine\Order\Application\Services\Handlers\Others;

use RedJasmine\Order\Application\Services\Handlers\AbstractOrderCommandHandler;
use RedJasmine\Order\Application\UserCases\Commands\Others\OrderUrgeCommand;
use RedJasmine\Support\Exceptions\AbstractException;

class OrderUrgeCommandHandler extends AbstractOrderCommandHandler
{

    /**
     * @param OrderUrgeCommand $command
     * @return void
     * @throws AbstractException
     */
    public function handle(OrderUrgeCommand $command) : void
    {
        $this->beginDatabaseTransaction();

        try {
            $order = $this->find($command->id);

            $order->urge();

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
