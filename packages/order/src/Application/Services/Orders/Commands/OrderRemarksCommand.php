<?php

namespace RedJasmine\Order\Application\Services\Orders\Commands;

use RedJasmine\Support\Data\Data;

class OrderRemarksCommand extends AbstractOrderCommand
{


    public ?int $orderProductId = null;

    public string $remarks;

    /**
     * 是否追加模式
     * @var bool
     */
    public bool $isAppend = false;

}
