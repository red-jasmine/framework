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
     * 支持优先级控制，优先级数值越小越先执行。
     *
     * @param  string  $hook  钩子的名称。
     * @param  mixed  $pipeline  管道的名称或实现，可以是一个字符串或实现了特定接口的对象。
     *                           支持数组格式: ['pipeline' => ClassName::class, 'priority' => 10]
     * @param  int  $priority  优先级，数值越小越先执行，默认为100。
     */
    public function register(string $hook, mixed $pipeline, int $priority = 100) : void
    {
        $pipelines = is_array($pipeline) ? $pipeline : [$pipeline];

        foreach ($pipelines as $item) {
            // 如果是数组且包含pipeline键，则使用其中的优先级
            if (is_array($item) && isset($item['pipeline'])) {
                $pipelineItem = $item['pipeline'];
                $itemPriority = $item['priority'] ?? $priority;
            } else {
                $pipelineItem = $item;
                $itemPriority = $priority;
            }

            // 初始化钩子数组
            if (!isset($this->hooks[$hook])) {
                $this->hooks[$hook] = [];
            }

            // 添加管道项，包含优先级信息
            $this->hooks[$hook][] = [
                'pipeline' => $pipelineItem,
                'priority' => $itemPriority,
            ];
        }

        // 按优先级排序(数值越小越先执行)
        if (isset($this->hooks[$hook])) {
            usort($this->hooks[$hook], fn($a, $b) => $a['priority'] <=> $b['priority']);
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
     * 返回按优先级排序后的管道列表。
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
        $registeredHooks = $this->hooks[$hook] ?? [];

        // 合并配置和注册的钩子
        $allHooks = [...$configHooks, ...$registeredHooks];

        // 按优先级排序
        usort($allHooks, fn($a, $b) =>
            ($a['priority'] ?? 100) <=> ($b['priority'] ?? 100)
        );

        // 提取管道类/闭包
        return array_map(
            fn($item) => is_array($item) ? $item['pipeline'] : $item,
            $allHooks
        );
    }

    /**
     * 从配置中获取钩子的管道列表。
     * 支持从配置文件中读取钩子定义，包括优先级信息。
     *
     * 配置格式示例:
     * 'hooks' => [
     *     'order.application.order.command.create' => [
     *         ['pipeline' => NotificationHook::class, 'priority' => 10],
     *         ['pipeline' => LogHook::class, 'priority' => 20],
     *     ],
     * ]
     *
     * @param  string  $hook  钩子的名称。
     *
     * @return array 从配置中获取的管道列表，包含优先级信息。
     */
    protected function getConfigHookPipelines(string $hook) : array
    {
        $config = config('hooks.' . $hook, []);

        // 如果配置为空，返回空数组
        if (empty($config)) {
            return [];
        }

        // 标准化配置格式
        $pipelines = [];
        foreach ($config as $item) {
            if (is_array($item) && isset($item['pipeline'])) {
                // 已经是标准格式
                $pipelines[] = [
                    'pipeline' => $item['pipeline'],
                    'priority' => $item['priority'] ?? 100,
                ];
            } elseif (is_string($item) || $item instanceof Closure) {
                // 简单格式，使用默认优先级
                $pipelines[] = [
                    'pipeline' => $item,
                    'priority' => 100,
                ];
            }
        }

        return $pipelines;
    }
}
