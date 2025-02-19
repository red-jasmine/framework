<?php

namespace RedJasmine\Order\Application\Services\Orders\Commands;

use RedJasmine\Support\Data\Data;

/**
 *  拒单
 */
class OrderRejectCommand extends Data
{

    public int $id;

    public ?string $reason = null;


}
