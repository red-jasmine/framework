<?php

namespace RedJasmine\Order\Application\UserCases\Commands;

use RedJasmine\Support\Data\Data;

class OrderCancelCommand extends Data
{

    public function __construct(
        public int     $id,
        public ?string $cancelReason = null
    )
    {
    }

}
