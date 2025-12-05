<?php

namespace RedJasmine\Order\Application\Services\Orders\Commands;

use RedJasmine\Order\Application\Services\Handlers\Others\Throwable;
use RedJasmine\Support\Exceptions\BaseException;

class OrderUrgeCommandHandler extends AbstractOrderCommandHandler
{

    /**
     * @param OrderUrgeCommand $command
     *
     * @return void
     * @throws BaseException
     */
    public function handle(OrderUrgeCommand $command) : void
    {
        $this->beginDatabaseTransaction();

        try {
            $order = $this->findByNo($command->orderNo);

            $order->urge();

            $this->service->repository->update($order);

            $this->commitDatabaseTransaction();
        } catch (BaseException $exception) {
            $this->rollBackDatabaseTransaction();
            throw  $exception;
        } catch (Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw  $throwable;
        }

    }
}
