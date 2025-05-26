<?php

namespace RedJasmine\Order\Application\Services\Orders\Commands;

use RedJasmine\Order\Application\Services\Handlers\AbstractException;
use RedJasmine\Order\Domain\Events\OrderPaidEvent;
use RedJasmine\Order\Domain\Exceptions\OrderException;
use Throwable;

class OrderRejectCommandHandler extends AbstractOrderCommandHandler
{


    /**
     * @param OrderRejectCommand $command
     * @return bool
     * @throws Throwable
     * @throws OrderException
     */
    public function handle(OrderRejectCommand $command) : bool
    {

        $this->beginDatabaseTransaction();

        try {
            $order = $this->findByNo($command->orderNo);

            $order->reject($command->reason);

            $this->service->repository->update($order);

            $this->commitDatabaseTransaction();
        } catch (AbstractException $exception) {
            $this->rollBackDatabaseTransaction();
            throw  $exception;
        } catch (Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw  $throwable;
        }

        OrderPaidEvent::dispatch($order);
        return true;
    }

}
