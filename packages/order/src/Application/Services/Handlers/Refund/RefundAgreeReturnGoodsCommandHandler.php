<?php

namespace RedJasmine\Order\Application\Services\Handlers\Refund;

use RedJasmine\Order\Application\UserCases\Commands\Refund\RefundAgreeRefundCommand;
use RedJasmine\Order\Application\UserCases\Commands\Refund\RefundAgreeReturnGoodsCommand;

class RefundAgreeReturnGoodsCommandHandler extends AbstractRefundCommandHandler
{


    public function handle(RefundAgreeReturnGoodsCommand $command) : void
    {
        $refund = $this->find($command->rid);

        $this->execute(
            execute: fn() => $refund->agreeReturnGoods(),
            persistence: fn() => $this->refundRepository->update($refund)
        );
    }

}
