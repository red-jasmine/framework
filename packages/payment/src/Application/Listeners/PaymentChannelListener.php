<?php

namespace RedJasmine\Payment\Application\Listeners;

use RedJasmine\Payment\Application\Services\Transfer\Commands\TransferExecutingCommand;

class PaymentChannelListener
{

    public function handler($event)
    {

        $this->transferHandler($event);
    }

    protected function transferHandler($event) : void
    {
        if ($event instanceof TransferExecutingCommand) {
            // 调度
        }

    }

}
