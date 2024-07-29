<?php

namespace RedJasmine\Order\Application\Services\Handlers\Refund;

use RedJasmine\Order\Application\UserCases\Commands\Refund\RefundConfirmCommand;

class RefundConfirmCommandHandler extends AbstractRefundCommandHandler
{

    public function handle(RefundConfirmCommand $command) : void
    {
        $refund = $this->find($command->rid);

        $this->execute(
            execute: fn() => $refund->confirm(),
            persistence: fn() => $this->refundRepository->update($refund)
        );


    }

}
