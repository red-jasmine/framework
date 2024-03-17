<?php

namespace RedJasmine\Support\Foundation\Service;

use BadMethodCallException;
use Closure;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Traits\Macroable;
use RedJasmine\Support\Helpers\ID\Snowflake;

abstract class Service
{

    use HasActions;

    use WithUserService;

    use WithClientService;


    /**
     * @return int
     * @throws Exception
     */
    public static function buildID() : int
    {
        return Snowflake::getInstance()->nextId();
    }


    /**
     * @return string|null|Model
     */
    public static function getModel() : ?string
    {
        return static::$model;
    }

    public static function getDataClass()
    {
        return static::$data;
    }
}
