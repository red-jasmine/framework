<?php

namespace RedJasmine\Order\Application\UserCases\Commands;

use RedJasmine\Support\Data\Data;

class OrderProgressCommand extends Data
{
    public int $id;

    public int $orderProductId;

    public int $progress;

    /**
     * 是否为绝对值
     * @var bool
     */
    public bool $isAbsolute = true;

    /**
     * 是否允许小于之前的值
     * @var bool
     */
    public bool $isAllowLess = false;

}
