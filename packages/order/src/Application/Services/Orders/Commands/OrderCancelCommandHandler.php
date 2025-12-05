<?php

namespace RedJasmine\Order\Application\Services\Orders\Commands;

use RedJasmine\Order\Domain\Exceptions\OrderException;
use RedJasmine\Support\Exceptions\BaseException;
use Throwable;

class OrderCancelCommandHandler extends AbstractOrderCommandHandler
{

    /**
     * @param OrderCancelCommand $command
     *
     * @return void
     * @throws BaseException
     * @throws Throwable
     * @throws OrderException
     */
    public function handle(OrderCancelCommand $command) : void
    {

        $this->beginDatabaseTransaction();

        try {

            $order = $this->findByNo($command->orderNo);
            $order->cancel($command->cancelReason);
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
