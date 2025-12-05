<?php

namespace RedJasmine\Payment\Application\Services\AsyncNotify\Commands;

use RedJasmine\Support\Foundation\Data\Data;

class NotifySendCommand extends Data
{
    public function __construct(
        public string $notifyNo
    )
    {
    }

}
