<?php

namespace RedJasmine\Order\Application\Services\Orders\Commands;

class OrderProgressCommand extends AbstractOrderCommand
{


    public string $orderProductNo;

    public int $progress;


    /**
     * 是否追加模式
     * @var bool
     */
    public bool $isAppend = false;

    /**
     * 是否允许小于之前的值
     * @var bool
     */
    public bool $isAllowLess = false;

}
