<?php

namespace RedJasmine\Order\Application\Services\Orders\Commands;

use RedJasmine\Order\Application\Services\Handlers\Others\Throwable;
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
            $order = $this->findByNo($command->orderNo);

            $order->urge();

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
