<?php

namespace RedJasmine\Order\Application\Services\Handlers\Refund;

use RedJasmine\Order\Application\UserCases\Commands\Refund\RefundCancelCommand;

class RefundCancelCommandHandler extends AbstractRefundCommandHandler
{



    public function handle(RefundCancelCommand $command) : void
    {

        $refund  = $this->find($command->rid);

        $this->execute(
            execute: fn() => $refund->cancel(),
            persistence: fn() => $this->refundRepository->update($refund)
        );

    }

}
