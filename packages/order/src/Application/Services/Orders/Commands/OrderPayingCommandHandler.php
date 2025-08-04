<?php

namespace RedJasmine\Order\Application\Services\Orders\Commands;

use RedJasmine\Order\Application\Services\Handlers\AbstractException;
use RedJasmine\Order\Domain\Exceptions\OrderException;
use RedJasmine\Order\Domain\Models\OrderPayment;
use Throwable;

class OrderPayingCommandHandler extends AbstractOrderCommandHandler
{


    /**
     * @param  OrderPayingCommand  $command
     *
     * @return OrderPayment
     * @throws Throwable
     * @throws OrderException
     */
    public function handle(OrderPayingCommand $command) : OrderPayment
    {
        $this->context->setCommand($command);

        $this->beginDatabaseTransaction();

        try {
            $order = $this->findByNo($command->orderNo);

            $orderPayment                 = OrderPayment::make();

            $orderPayment->payment_amount = $command->amount ?? $order->payable_amount;
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
