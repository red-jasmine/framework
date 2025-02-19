<?php

namespace RedJasmine\Order\Application\Services\Refunds\Commands;

use RedJasmine\Support\Data\Data;

class RefundConfirmCommand extends Data
{
    /**
     * 售后ID
     * @var int
     */
    public int $id;
}
