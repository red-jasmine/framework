<?php

namespace RedJasmine\Support\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Hook 门面类
 *
 * @method static void register(string $hook, mixed $pipeline, int $priority = 100) 注册钩子管道，支持优先级控制(数值越小越先执行)
 * @method static mixed hook(string $hook, mixed $passable, \Closure $destination) 执行钩子管道
 *
 * @see \RedJasmine\Support\Foundation\Hook\HookManage
 */
class Hook extends Facade
{

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() : string
    {
        return 'hook';
    }

}
