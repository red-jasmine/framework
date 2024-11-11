<?php

namespace RedJasmine\Order\Application\Services\Handlers\Payments;

use RedJasmine\Order\Application\UserCases\Commands\Payments\OrderPaymentPayingCommand;
use RedJasmine\Support\Exceptions\AbstractException;
use Throwable;

class OrderPaymentPayingCommandHandler extends AbstractOrderPaymentCommandHandler
{


    /**
     * @param OrderPaymentPayingCommand $command
     * @return void
     * @throws AbstractException
     * @throws Throwable
     */
    public function handle(OrderPaymentPayingCommand $command)
    {
        $this->beginDatabaseTransaction();

        try {

            $orderPayment = $this->orderPaymentRepository->find($command->id);

            $orderPayment->paying($command);

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
