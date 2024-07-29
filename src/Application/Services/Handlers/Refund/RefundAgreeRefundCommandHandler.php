<?php

namespace RedJasmine\Order\Application\Services\Handlers\Refund;

use RedJasmine\Order\Application\UserCases\Commands\Refund\RefundAgreeRefundCommand;
use RedJasmine\Order\Domain\Exceptions\RefundException;

class RefundAgreeRefundCommandHandler extends AbstractRefundCommandHandler
{


    public function handle(RefundAgreeRefundCommand $command) : void
    {

        $refund = $this->find($command->rid);
        $this->execute(
            execute: fn() => $refund->agreeRefund($command->amount),
            persistence: fn() => $this->refundRepository->update($refund),
        );

    }

}
