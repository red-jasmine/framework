<?php

namespace RedJasmine\Order\Application\Services\Refunds\Commands;

use RedJasmine\Support\Exceptions\AbstractException;
use Throwable;

class RefundUrgeCommandHandler extends AbstractRefundCommandHandler
{


    public function handle(RefundUrgeCommand $command) : void
    {
        $this->beginDatabaseTransaction();

        try {
            $refund = $this->findByNo($command->refundNo);

            $refund->urge();

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
