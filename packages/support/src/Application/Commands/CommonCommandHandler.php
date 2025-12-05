<?php

namespace RedJasmine\Support\Application\Commands;

use Closure;
use Illuminate\Database\Eloquent\Model;
use RedJasmine\Support\Application\ApplicationService;
use RedJasmine\Support\Foundation\Data\Data;
use Throwable;

/**
 * 精细化流程类
 *
 * @property ApplicationService $service
 */
abstract class CommonCommandHandler extends CommandHandler
{
    protected string $name;

    /**
     * 处理命令对象
     *
     * @param  Data  $command  被处理的命令对象
     *
     * @return Model|null 返回处理后的模型对象或其他相关结果
     * @throws Throwable
     */
    public function handle($command) : ?Model
    {


        $this->getContext()->setCommand($command);
        // 执行前置验证
        $this->callHook('validate', $this->context, fn() => $this->validate($this->getContext()));

        // 开始数据库事务
        $this->beginDatabaseTransaction();
        try {
            // 解析模型
            $model = $this->callHook('resolve', $this->context, fn() => $this->resolve($this->getContext()));
            // 设置模型到上下文
            $this->getContext()->setModel($model);
            // 支持具体逻辑
            $this->callHook('execute', $this->context, fn() => $this->execute($this->getContext()));
            // 对数据进行持久化
            $this->callHook('persist', $this->context, fn() => $this->persist($this->getContext()));
            // 提交事务
            $this->commitDatabaseTransaction();
        } catch (Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw $throwable;
        }

        return $this->getContext()->getModel();
    }

    /**
     * @param  string  $hook
     * @param  mixed  $passable
     * @param  Closure  $destination
     *
     * @return mixed
     */
    protected function callHook(string $hook, mixed $passable, Closure $destination) : mixed
    {
        $hook = $this->name.'.'.$hook;
        // 调用 服务的钩子？ TODO
        return $this->service->hook($hook, $passable, $destination);
    }

    /**
     * 前置验证
     *
     * @param  CommandContext  $context
     *
     * @return void
     */
    abstract protected function validate(CommandContext $context) : void;

    /**
     * 获取模型对象
     *
     * @param  CommandContext  $context
     *
     * @return Model
     */
    abstract protected function resolve(CommandContext $context) : Model;

    /**
     * 执行命令
     *
     * @param  CommandContext  $context
     *
     */
    abstract protected function execute(CommandContext $context);

    /**
     * 持久化
     *
     * @param  CommandContext  $context
     *
     *
     */
    abstract protected function persist(CommandContext $context);

}
