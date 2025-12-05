<?php

namespace RedJasmine\Order\Application\Services\Payments\Commands;

use RedJasmine\Support\Exceptions\BaseException;
use Throwable;

class OrderPaymentFailCommandHandler extends AbstractOrderPaymentCommandHandler
{


    /**
     * @param OrderPaymentFailCommand $command
     *
     * @return void
     * @throws BaseException
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
        } catch (BaseException $exception) {
            $this->rollBackDatabaseTransaction();
            throw  $exception;
        } catch (Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw  $throwable;
        }


    }

}
