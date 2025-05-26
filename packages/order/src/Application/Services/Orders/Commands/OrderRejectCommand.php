<?php

namespace RedJasmine\Order\Application\Services\Orders\Commands;

use RedJasmine\Support\Data\Data;

/**
 *  拒单
 */
class OrderRejectCommand extends AbstractOrderCommand
{



    public ?string $reason = null;


}
