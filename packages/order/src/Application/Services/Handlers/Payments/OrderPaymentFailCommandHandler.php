<?php

namespace RedJasmine\Order\Application\Services\Handlers\Payments;

use RedJasmine\Order\Application\UserCases\Commands\Payments\OrderPaymentFailCommand;
use RedJasmine\Support\Exceptions\AbstractException;
use Throwable;

class OrderPaymentFailCommandHandler extends AbstractOrderPaymentCommandHandler
{


    /**
     * @param OrderPaymentFailCommand $command
     * @return void
     * @throws AbstractException
     * @throws Throwable
     */
    public function handle(OrderPaymentFailCommand $command) : void
    {
        $this->beginDatabaseTransaction();

        try {

            $orderPayment = $this->orderPaymentRepository->find($command->id);

            $orderPayment->fail($command);

            $this->orderPaymentRepository->update($orderPayment);

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
