<?php

namespace RedJasmine\Support\Foundation\Facades;

use Illuminate\Support\Facades\Facade;
use RedJasmine\Support\Foundation\Context\ServiceContextManage;

/**
 * @method static get($key)
 * @method static getOperator()
 * @method static setOperator($operator)
 * @method static put($key, $value)
 */
class ServiceContext extends Facade
{

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() : string
    {
        return ServiceContextManage::class;
    }

}
