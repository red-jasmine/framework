<?php

namespace RedJasmine\Order\Application\Services\Handlers;

use RedJasmine\Order\Application\UserCases\Commands\OrderAcceptCommand;
use RedJasmine\Order\Application\UserCases\Commands\OrderRejectCommand;
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
            $order = $this->find($command->id);

            $order->reject($command->reason);

            $this->orderRepository->update($order);

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
