<?php

namespace RedJasmine\Order\Application\Services\Orders\Commands;

use RedJasmine\Order\Application\Services\Handlers\AbstractException;
use RedJasmine\Order\Domain\Models\OrderPayment;
use Throwable;

class OrderPayingCommandHandler extends AbstractOrderCommandHandler
{


    public function handle(OrderPayingCommand $command) : OrderPayment
    {


        $this->beginDatabaseTransaction();

        try {
            $order = $this->find($command->id);

            $orderPayment                 = OrderPayment::make();
            $orderPayment->payment_amount = $command->amount;
            $orderPayment->amount_type    = $command->amountType;

            $order->paying($orderPayment);


            $this->service->repository->store($order);
            $this->commitDatabaseTransaction();
        } catch (AbstractException $exception) {
            $this->rollBackDatabaseTransaction();
            throw  $exception;
        } catch (Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw  $throwable;
        }


        return $orderPayment;
    }

}
