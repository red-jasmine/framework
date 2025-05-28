<?php

namespace RedJasmine\Order\Domain\Facades;

use Illuminate\Support\Facades\Facade;
use RedJasmine\Order\Domain\Types\OrderTypeManage;

/**
 * @see OrderTypeManage
 */
class OrderType extends Facade
{
    protected static function getFacadeAccessor() : string
    {
        return OrderTypeManage::class;
    }
}
