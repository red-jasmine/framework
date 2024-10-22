<?php

namespace RedJasmine\Order\Application\UserCases\Commands\Shipping;

use RedJasmine\Order\Domain\Models\Enums\OrderCardKeyStatusEnum;
use RedJasmine\Support\Data\Data;

class OrderCardKeyShippingCommand extends Data
{
    public int $id;

    public int $orderProductId;

    /**
     * 内容
     * @var string
     */
    public string $content;


    /**
     * 数量
     * @var int
     */
    public int $num = 1;


    /**
     * 扩展信息
     * @var array
     */
    public array $extends = [];


    public OrderCardKeyStatusEnum $status = OrderCardKeyStatusEnum::SHIPPED;

}
