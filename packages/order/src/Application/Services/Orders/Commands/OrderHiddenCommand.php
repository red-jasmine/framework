<?php

namespace RedJasmine\Order\Application\Services\Orders\Commands;



class OrderHiddenCommand extends AbstractOrderCommand
{



    /**
     * 隐藏或者显示
     * @var bool
     */
    public bool $isHidden = true;

}
