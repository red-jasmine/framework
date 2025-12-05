<?php

namespace RedJasmine\Order\Application\Services\Orders\Commands;

use RedJasmine\Order\Application\Services\Handlers\Others\Throwable;
use RedJasmine\Support\Exceptions\BaseException;

class OrderStarCommandHandler extends AbstractOrderCommandHandler
{

    /**
     * @param OrderStarCommand $command
     *
     * @return void
     * @throws BaseException
     */
    public function handle(OrderStarCommand $command) : void
    {
        $this->beginDatabaseTransaction();

        try {
            $order = $this->findByNo($command->orderNo);


            $order->star($command->star);

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
