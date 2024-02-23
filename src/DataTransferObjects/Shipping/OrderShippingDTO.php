<?php

namespace RedJasmine\Order\DataTransferObjects\Shipping;

use RedJasmine\Order\DataTransferObjects\OrderSplitProductDTO;

class OrderShippingDTO extends OrderSplitProductDTO
{
    /**
     * 是否拆分
     * @var bool
     */
    public bool $isSplit = false;

    /**
     * 部分订单商品 集合
     * @var array|null
     */
    public ?array $orderProducts = null;
}
