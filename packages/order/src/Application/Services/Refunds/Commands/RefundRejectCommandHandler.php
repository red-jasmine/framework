<?php

namespace RedJasmine\Order\Application\Services\Refunds\Commands;

use RedJasmine\Support\Exceptions\AbstractException;
use Throwable;

class RefundRejectCommandHandler extends AbstractRefundCommandHandler
{


    public function handle(RefundRejectCommand $command) : void
    {

        $this->beginDatabaseTransaction();

        try {
            $refund = $this->find($command->id);

            $refund->reject($command->reason);


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
