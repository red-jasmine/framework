<?php

namespace RedJasmine\Order\Application\Services\Orders\Commands;

use RedJasmine\Support\Data\Data;

class OrderHiddenCommand extends Data
{
    public int $id;


    /**
     * 隐藏或者显示
     * @var bool
     */
    public bool $isHidden = true;

}
