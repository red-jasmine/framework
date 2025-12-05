<?php

namespace RedJasmine\Order\Application\Services\Refunds\Commands;

use RedJasmine\Support\Exceptions\BaseException;
use Throwable;

class RefundConfirmCommandHandler extends AbstractRefundCommandHandler
{

    public function handle(RefundConfirmCommand $command) : void
    {
        $this->beginDatabaseTransaction();

        try {
            $refund = $this->find($command->id);
            $refund->confirm();

            $this->service->repository->update($refund);

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
