<?php

namespace RedJasmine\Order\Application\Services\Handlers;

use RedJasmine\Order\Application\UserCases\Commands\OrderAcceptCommand;
use RedJasmine\Order\Domain\Events\OrderPaidEvent;
use Throwable;
use RedJasmine\Order\Domain\Exceptions\OrderException;

class OrderAcceptCommandHandler extends AbstractOrderCommandHandler
{


    /**
     * @param OrderAcceptCommand $command
     * @return bool
     * @throws Throwable
     * @throws OrderException
     */
    public function handle(OrderAcceptCommand $command) : bool
    {

        $this->beginDatabaseTransaction();

        try {
            $order = $this->find($command->id);

            $order->accept();

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
