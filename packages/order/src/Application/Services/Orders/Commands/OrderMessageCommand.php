<?php

namespace RedJasmine\Order\Application\Services\Orders\Commands;

class OrderMessageCommand extends AbstractOrderCommand
{


    public ?string $orderProductNo = null;

    public string $message;

    /**
     * 是否追加模式
     * @var bool
     */
    public bool $isAppend = false;

}
