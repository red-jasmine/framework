<?php

namespace RedJasmine\Order\Application\UserCases\Commands\Refund;

use RedJasmine\Support\Data\Data;

class RefundConfirmCommand extends Data
{
    /**
     * 售后ID
     * @var int
     */
    public int $id;
}
