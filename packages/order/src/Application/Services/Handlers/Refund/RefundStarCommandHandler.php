<?php

namespace RedJasmine\Order\Application\Services\Handlers\Refund;

use RedJasmine\Order\Application\UserCases\Commands\Refund\RefundStarCommand;
use RedJasmine\Support\Exceptions\AbstractException;
use Throwable;

class RefundStarCommandHandler extends AbstractRefundCommandHandler
{


    public function handle(RefundStarCommand $command) : void
    {
        $this->beginDatabaseTransaction();

        try {
            $refund = $this->find($command->id);

            $refund->star($command->star);

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
