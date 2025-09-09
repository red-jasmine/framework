<?php

namespace RedJasmine\Support\Application\Commands;

use Closure;
use Illuminate\Database\Eloquent\Model;
use RedJasmine\Support\Application\HandleContext;
use RedJasmine\Support\Data\Data;
use Throwable;
use RedJasmine\Support\Application\ApplicationService;
/**
 * @property ApplicationService $service
 */
abstract class BaseCommandHandler extends CommandHandler
{
    abstract protected string $name {
        get;
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
        return $this->service->hook($hook, $passable, $destination);
    }

    /**
     * 处理命令对象
     *
     * @param  Data  $command  被处理的命令对象
     *
     * @return Model|null 返回处理后的模型对象或其他相关结果
     * @throws Throwable
     */
    public function handle(Data $command) : ?Model
    {

        $this->context->setCommand($command);

        // 开始数据库事务
        $this->beginDatabaseTransaction();
        try {
            // 初始化模型
            $this->context->setModel($this->getModel($command));
            // 对数据进行验证
            $this->callHook('validate', $this->context, fn() => $this->validate($this->context));
            // 填充模型
            $this->callHook('fill', $this->context, fn() => $this->fill($this->context));
            // 存储模型到仓库
            $this->callHook('save', $this->context, fn() => $this->save($this->context));
            // 提交事务
            $this->commitDatabaseTransaction();
        } catch (Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw $throwable;
        }

        return $this->context->getModel();
    }


    abstract protected function getModel(Data $command) : Model;

    abstract protected function validate(HandleContext $context) : void;

    abstract protected function fill(HandleContext $context) : void;

    abstract protected function save(HandleContext $context) : void;

}
