<?php

namespace RedJasmine\Order\Application\Services\Orders\Commands;

use RedJasmine\Order\Domain\Data\LogisticsData;

class OrderLogisticsShippingCommand extends LogisticsData
{

    public ?string $orderNo;
    public ?int    $id;
    /**
     * 是否完成发货
     * @var bool
     */
    public bool $isFinished = true;


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
