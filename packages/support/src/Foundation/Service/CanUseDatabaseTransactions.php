<?php

namespace RedJasmine\Support\Foundation\Service;

use Closure;
use Illuminate\Support\Facades\DB;
use Throwable;

/**
 * 提供数据库事务处理能力的trait。
 * 通过这个trait，可以控制何时开始、提交或回滚数据库事务。
 */
trait CanUseDatabaseTransactions
{
    // 标记是否启用了数据库事务
    protected bool|Closure $hasDatabaseTransactions = true;


    /**
     * 控制数据库事务的启用状态。
     *
     * @param  bool|Closure  $condition  一个布尔值或闭包，用于决定是否启用数据库事务。
     *
     * @return static
     */
    public function databaseTransaction(bool|Closure $condition = true) : static
    {
        $this->hasDatabaseTransactions = $condition;
        return $this;
    }

    /**
     * 开始一个新的数据库事务。
     * 如果事务未启用，则不执行任何操作。
     * @return void
     * @throws Throwable
     */
    public function beginDatabaseTransaction() : void
    {
        if (!$this->hasDatabaseTransactions()) {
            return;
        }

        DB::beginTransaction();
    }


    /**
     * 检查是否有数据库事务正在进行。
     *
     * 此方法首先检查是否存在一个可调用的事务检查机制。如果存在，则调用该机制。
     * 否则，它将基于内部状态判断是否有事务正在进行中。
     *
     * @return bool 如果有数据库事务正在进行，则返回true；否则返回false。
     */
    public function hasDatabaseTransactions() : bool

    {
        // 如果hasDatabaseTransactions是可调用的（例如，它是一个闭包或回调函数），则调用它
        if (is_callable($this->hasDatabaseTransactions)) {
            $callable = $this->hasDatabaseTransactions;
            return (bool) $callable();
        }
        // 否则，根据内部变量hasDatabaseTransactions的值来判断是否有事务正在进行中
        return (bool) $this->hasDatabaseTransactions;
    }


    /**
     * 提交当前的数据库事务。
     * 如果事务未启用，则不执行任何操作。
     * @return void
     * @throws Throwable
     */
    public function commitDatabaseTransaction() : void
    {
        if (!$this->hasDatabaseTransactions()) {
            return;
        }

        DB::commit();
    }

    /**
     * 回滚当前的数据库事务。
     * 如果事务未启用，则不执行任何操作。
     * @return void
     * @throws Throwable
     */
    public function rollBackDatabaseTransaction() : void
    {
        if (!$this->hasDatabaseTransactions()) {
            return;
        }

        DB::rollBack();
    }
}
