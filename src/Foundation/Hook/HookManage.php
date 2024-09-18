<?php

namespace RedJasmine\Support\Foundation\Hook;

use Closure;
use Illuminate\Pipeline\Pipeline;

/**
 * 钩子管理类，用于注册和调用钩子及其对应的管道。
 */
class HookManage
{
    // 单例实例
    // 钩子及其对应的管道列表
    private array $hooks = [];

    /**
     * 注册一个钩子及其对应的管道。
     * 如果管道是一个字符串并且尚未注册，则直接注册。
     * 否则，将管道添加到注册的管道列表中。
     *
     * @param  string  $hook  钩子的名称。
     * @param  mixed  $pipeline  管道的名称或实现，可以是一个字符串或实现了特定接口的对象。
     */
    public function register(string $hook, mixed $pipeline) : void
    {
        $pipelines = is_array($pipeline) ? $pipeline : [$pipeline];

        foreach ($pipelines as $item) {
            if (is_string($item) && !isset($this->hooks[$hook][$item])) {

                $this->hooks[$hook][$item] = $item;

            } else {
                $this->hooks[$hook][] = $item;
            }
        }

    }

    /**
     * 调用钩子，并通过管道处理传递的数据。
     * 根据钩子名称获取已注册的管道，并使用它们处理传递的数据，最后将处理后的数据传递给目标闭包函数。
     *
     * @param  string  $hook  钩子的名称。
     * @param  mixed  $passable  要通过管道处理的数据。
     * @param  Closure  $destination  目标闭包函数，在管道处理完成后执行。
     *
     * @return mixed 目标闭包函数的返回值。
     */
    public function hook(string $hook, mixed $passable, Closure $destination) : mixed
    {
        return app(Pipeline::class)
            ->send($passable)
            ->pipe($this->getHookPipelines($hook))
            ->then($destination);
    }

    /**
     * 获取钩子的管道列表。
     * 优先从配置中获取管道，如果配置中没有，则从注册的管道中获取。
     *
     * @param  string  $hook  钩子的名称。
     *
     * @return array 钩子的管道列表。
     */
    protected function getHookPipelines(string $hook) : array
    {
        // 通过配置获取
        $configHooks = $this->getConfigHookPipelines($hook);
        // 通过注册添加
        $hooks = $this->hooks[$hook] ?? [];
        return [...$configHooks, ...array_values($hooks)];
    }

    /**
     * 从配置中获取钩子的管道列表。
     * 此方法目前未实现，需要根据实际情况进行实现。
     *
     * @param  string  $hook  钩子的名称。
     *
     * @return array 从配置中获取的管道列表。
     */
    protected function getConfigHookPipelines(string $hook) : array
    {
        // TODO 获取配置信息
        return [];
    }
}
