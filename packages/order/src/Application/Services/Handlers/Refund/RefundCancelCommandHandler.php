<?php

namespace RedJasmine\Order\Application\Services\Handlers\Refund;

use RedJasmine\Order\Application\UserCases\Commands\Refund\RefundCancelCommand;
use RedJasmine\Support\Exceptions\AbstractException;
use Throwable;

class RefundCancelCommandHandler extends AbstractRefundCommandHandler
{


    public function handle(RefundCancelCommand $command) : void
    {
        $this->beginDatabaseTransaction();

        try {
            $refund = $this->find($command->id);

            $refund->cancel();
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
