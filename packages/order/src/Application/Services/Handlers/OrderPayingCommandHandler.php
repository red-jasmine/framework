<?php

namespace RedJasmine\Order\Application\Services\Handlers;

use Illuminate\Support\Facades\DB;
use RedJasmine\Order\Application\UserCases\Commands\OrderPayingCommand;
use RedJasmine\Order\Domain\Models\OrderPayment;
use Throwable;

class OrderPayingCommandHandler extends AbstractOrderCommandHandler
{


    public function handle(OrderPayingCommand $command) : OrderPayment
    {


        $this->beginDatabaseTransaction();

        try {
            $order = $this->find($command->id);

            $orderPayment                 = OrderPayment::newModel();
            $orderPayment->payment_amount = $command->amount;
            $orderPayment->amount_type    = $command->amountType;

            $order->paying($orderPayment);


            $this->orderRepository->store($order);
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
