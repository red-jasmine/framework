<?php

namespace RedJasmine\Support\Facades;

use Illuminate\Support\Facades\Facade;
use RedJasmine\Support\Infrastructure\ServiceContextManage;

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
