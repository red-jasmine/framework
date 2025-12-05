<?php

namespace RedJasmine\Support\Foundation\Hook;


use Closure;
use RedJasmine\Support\Foundation\Facades\Hook;

/**
 * 有 hooks 能力
 */
trait HasHooks
{

    /**
     * 钩子前缀
     * @var string
     */
    public static string $hookNamePrefix;

    /**
     * 执行钩子操作
     *
     * 本函数用于执行特定的钩子，通过内部调用 Hook Facade 来处理，
     * 允许在不同部分的代码之间插入自定义功能。
     *
     * @param  string  $hook  钩子的名称，用于标识需要执行的操作
     * @param  mixed  $passable  传递给钩子函数的参数，类型根据实际情况而定
     * @param  Closure  $destination  钩子触发时要执行的闭包函数
     *
     * @return mixed 钩子函数的执行结果，类型根据实际情况而定
     */
    public function hook(string $hook, mixed $passable, Closure $destination) : mixed
    {
        // 注册一次默认方法内的钩子
        $this->registerDefaultHooks();
        // 调用 Hook Facade 的 hook 方法来执行钩子，传入钩子名称、参数和目标闭包
        // 使用之前定义的 getHookName 方法来处理钩子名称，确保一致性

        return Hook::hook(
            $this->getHookName($hook),
            $passable,
            $destination);


    }

    protected function registerDefaultHooks() : void
    {
        foreach ($this->hooks() as $hook => $pipelines) {
            static::registerHook($hook, $pipelines);
        }

    }

    protected function hooks() : array
    {
        return [];
    }

    /**
     * 注册钩子
     *
     * @param  string  $hook  钩子名称
     * @param  mixed  $pipeline  管道处理器
     * @param  int  $priority  优先级，数值越小越先执行，默认为100
     *
     * @return void
     */
    public static function registerHook(string $hook, mixed $pipeline, int $priority = 100) : void
    {
        if (static::getHookName($hook)) {
            Hook::register(static::getHookName($hook), $pipeline, $priority);
        }

    }

    /**
     * 获取钩子的完整名称
     *
     * 通过此方法可以获取带有前缀的钩子名称。它首先尝试调用实例方法 hookNamePrefix 获取前缀，
     * 如果该方法不存在，则使用类变量 $hookNamePrefix 作为前缀。最后将前缀和传入的钩子名称拼接
     * 并返回。
     *
     * @param  string  $hook  需要添加前缀的钩子名称
     *
     * @return string 带有前缀的完整钩子名称
     */
    public static function getHookName(string $hook) : string
    {
        $prefix = static::class;
        // 尝试调用实例方法获取前缀
        if (method_exists(static::class, 'hookNamePrefix')) {
            $prefix = (string) static::hookNamePrefix();
        } elseif (isset(static::$hookNamePrefix)) {
            // 如果实例方法不存在，则使用类变量作为前缀
            $prefix = static::$hookNamePrefix;
        }
        // 将前缀和钩子名称拼接并返回
        return $prefix.'.'.$hook;

    }


}
