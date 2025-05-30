<?php

namespace RedJasmine\Order\Application\Services\Orders\Commands;

class OrderRemarksCommand extends AbstractOrderCommand
{


    public ?string $orderProductNo = null;

    public string $remarks;

    /**
     * 是否追加模式
     * @var bool
     */
    public bool $isAppend = false;

}
