<?php

namespace RedJasmine\Support\Foundation\Hook;

use Closure;
use Illuminate\Pipeline\Pipeline;

class HookManage
{
    protected static array $hooks = [];


    public function register(string $hook, $pipeline) : void
    {
        if (is_string($pipeline) && !isset(self::$hooks[$hook][$pipeline])) {

            static::$hooks[$hook][$pipeline] = $pipeline;

        } else {
            static::$hooks[$hook][] = $pipeline;
        }
    }

    public function hook(string $hook, $passable, Closure $destination)
    {
        return app(Pipeline::class)
            ->send($passable)
            ->pipe($this->getHookPipelines($hook))
            ->then($destination);
    }


    protected function getHookPipelines(string $hook) : array
    {
        // 通过配置获取
        $configHooks = $this->getConfigHookPipelines($hook);
        // 通过注册添加
        $hooks = static::$hooks[$hook] ?? [];
        return [...$configHooks, ...array_values($hooks)];
    }

    protected function getConfigHookPipelines(string $hook) : array
    {
        // TODO 获取配置信息
        return [];
    }

}
