<?php

namespace RedJasmine\Payment\Application\Commands\Notify;

use RedJasmine\Support\Data\Data;

class NotifySendCommand extends Data
{
    public function __construct(
        public string $notifyNo
    )
    {
    }

}
