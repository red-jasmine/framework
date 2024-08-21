<?php

namespace RedJasmine\Support\Foundation\Hook;

use RedJasmine\Support\Facades\Hook;

/**
 * 钩子调用
 *
 */
trait Hookable
{


    /**
     * Dispatch the event with the given arguments.
     *
     * @return mixed
     */
    public static function hook()
    {
        return Hook::execute(static::getHookName(), ... func_get_args());
    }

    protected static function getHookName() : string
    {
        return static::$hook ?? static::class;
    }

    /**
     * 注册管道
     *
     * @param $pipeline
     *
     * @return void
     */
    public static function register($pipeline) : void
    {
        Hook::register(static::getHookName(), $pipeline);

    }

}
