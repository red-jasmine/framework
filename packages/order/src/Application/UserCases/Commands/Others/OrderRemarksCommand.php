<?php

namespace RedJasmine\Order\Application\UserCases\Commands\Others;

use RedJasmine\Support\Data\Data;

class OrderRemarksCommand extends Data
{
    public int $id;

    public ?int $orderProductId = null;

    public string $remarks;

    /**
     * 是否追加模式
     * @var bool
     */
    public bool $isAppend = false;

}
