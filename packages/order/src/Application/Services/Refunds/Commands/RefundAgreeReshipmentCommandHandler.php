<?php

namespace RedJasmine\Order\Application\Services\Refunds\Commands;


use RedJasmine\Support\Exceptions\BaseException;
use Throwable;

class RefundAgreeReshipmentCommandHandler extends AbstractRefundCommandHandler
{


    public function handle(RefundAgreeReshipmentCommand $command) : void
    {

        $this->beginDatabaseTransaction();

        try {
            $refund = $this->findByNo($command->refundNo);

            $refund->agreeReshipment();

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
