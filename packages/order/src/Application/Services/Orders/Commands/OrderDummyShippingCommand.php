<?php

namespace RedJasmine\Order\Application\Services\Orders\Commands;

use RedJasmine\Support\Data\Data;

class OrderDummyShippingCommand extends Data
{
    public int $id;

    /**
     * 部分订单商品 集合
     * @var array|null
     */
    public ?array $orderProducts = null;
    /**
     * 是否完成发货
     * @var bool
     */
    public bool $isFinished = true;
}
