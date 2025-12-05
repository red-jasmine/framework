<?php

namespace RedJasmine\Order\Application\Services\Payments\Commands;

use RedJasmine\Support\Exceptions\BaseException;
use Throwable;

class OrderPaymentPaidCommandHandler extends AbstractOrderPaymentCommandHandler
{


    /**
     * @param OrderPaymentPaidCommand $command
     *
     * @return void
     * @throws BaseException
     * @throws Throwable
     */
    public function handle(OrderPaymentPaidCommand $command)
    {
        $this->beginDatabaseTransaction();

        try {

            $orderPayment = $this->orderPaymentRepository->find($command->id);

            $orderPayment->paid($command);

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
