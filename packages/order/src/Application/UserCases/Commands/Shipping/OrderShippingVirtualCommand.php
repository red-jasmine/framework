<?php

namespace RedJasmine\Order\Application\UserCases\Commands\Shipping;

use RedJasmine\Support\Data\Data;

class OrderShippingVirtualCommand extends Data
{
    public int $id;

    public int $orderProductId;
    /**
     * 是否完成发货
     * @var bool
     */
    public bool $isFinished = true;
}
