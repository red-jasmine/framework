<?php

namespace RedJasmine\Support\Foundation\Hook;

use RedJasmine\Support\Foundation\Facades\Hook;

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
    public static function hook() : mixed
    {
        return Hook::hook(static::getHookName(), ... func_get_args());
    }

    /**
     * 获取钩子的名称
     *
     * 此方法用于获取当前类关联的钩子名称。钩子名称可以通过类属性静态设置。
     * 如果没有设置钩子名称，则返回当前类的名称作为默认钩子名称。
     *
     * @return string 钩子名称，如果未设置则为当前类名
     */
    protected static function getHookName() : string
    {
        // 返回静态属性 $hook 的值，如果未设置，则返回当前类的名称
        return static::$hook ?? static::class;
    }


    /**
     * 注册管道
     *
     * @param  mixed  $pipeline  管道处理器
     * @param  int  $priority  优先级，数值越小越先执行，默认为100
     *
     * @return void
     */
    public static function register(mixed $pipeline, int $priority = 100) : void
    {
        Hook::register(static::getHookName(), $pipeline, $priority);

    }

}
