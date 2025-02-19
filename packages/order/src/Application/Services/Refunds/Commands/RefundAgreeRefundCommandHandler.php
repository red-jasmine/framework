<?php

namespace RedJasmine\Order\Application\Services\Refunds\Commands;

use RedJasmine\Order\Application\Services\Handlers\Refund\AbstractException;
use Throwable;

class RefundAgreeRefundCommandHandler extends AbstractRefundCommandHandler
{


    public function handle(RefundAgreeRefundCommand $command) : void
    {

        $this->beginDatabaseTransaction();

        try {
            $refund = $this->find($command->id);

            $refund->agreeRefund($command->amount);

            $this->service->repository->update($refund);

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
