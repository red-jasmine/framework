<?php

namespace RedJasmine\Order\Application\UserCases\Commands\Shipping;

use RedJasmine\Order\Domain\Data\LogisticsData;

class OrderLogisticsShippingCommand extends LogisticsData
{

    public int $id;

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
