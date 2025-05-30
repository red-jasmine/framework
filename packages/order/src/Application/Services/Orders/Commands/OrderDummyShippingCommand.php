<?php

namespace RedJasmine\Order\Application\Services\Orders\Commands;

class OrderDummyShippingCommand extends AbstractOrderCommand
{


    /**
     * 部分订单商品 集合
     * @var array<string>|null
     */
    public ?array $orderProducts = null;
    /**
     * 是否完成发货
     * @var bool
     */
    public bool $isFinished = true;
}
