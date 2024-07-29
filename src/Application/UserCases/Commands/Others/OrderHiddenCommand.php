<?php

namespace RedJasmine\Order\Application\UserCases\Commands\Others;

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
