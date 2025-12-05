<?php

namespace RedJasmine\Support\Application\Commands;

use RedJasmine\Support\Foundation\Hook\HasHooks;
use RedJasmine\Support\Foundation\Service\CanUseDatabaseTransactions;


/**
 * 命令处理器基础类
 *
 * @property CommandContext $context
 */
abstract class CommandHandler
{
    /**
     *  Hook 能力
     */
    use HasHooks;

    /**
     * 数据库事务能力
     */
    use CanUseDatabaseTransactions;


    /**
     * 命令上下文
     * @var CommandContext
     */
    protected CommandContext $context;

    public function getContext() : CommandContext
    {
        if (isset($this->context)) {
            return $this->context;
        }
        $this->context = new CommandContext();
        return $this->context;
    }

    // 继承后完全 自定义 handle 方法


}
