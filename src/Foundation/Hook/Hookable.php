<?php

namespace RedJasmine\Support\Foundation\Hook;

use RedJasmine\Support\Facades\Hook;

/**
 * 钩子调用
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
        return Hook::execute(static::$hook ?? static::class, ... func_get_args());
    }

}
