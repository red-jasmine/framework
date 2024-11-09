<?php

namespace RedJasmine\Order\Application\Services\Handlers\Refund;

use RedJasmine\Order\Application\UserCases\Commands\Refund\RefundUrgeCommand;
use RedJasmine\Support\Exceptions\AbstractException;
use Throwable;

class RefundUrgeCommandHandler extends AbstractRefundCommandHandler
{


    public function handle(RefundUrgeCommand $command) : void
    {
        $this->beginDatabaseTransaction();

        try {
            $refund = $this->find($command->id);

            $refund->urge();

            $this->refundRepository->update($refund);


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
