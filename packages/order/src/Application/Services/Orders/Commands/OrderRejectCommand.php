<?php

namespace RedJasmine\Order\Application\Services\Orders\Commands;

/**
 *  拒单
 */
class OrderRejectCommand extends AbstractOrderCommand
{



    public ?string $reason = null;


}
