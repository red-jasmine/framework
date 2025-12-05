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
    use HasHooks;

    use CanUseDatabaseTransactions;



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
