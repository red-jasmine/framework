<?php

namespace RedJasmine\Support\Foundation\Service;


use Closure;
use Illuminate\Support\Facades\DB;

trait CanUseDatabaseTransactions
{

    protected ?bool $hasDatabaseTransactions = null;

    public function hasDatabaseTransactions() : bool
    {
        return (boolean)$this->hasDatabaseTransactions;
    }

    protected function beginDatabaseTransaction() : void
    {
        if (!$this->hasDatabaseTransactions()) {
            return;
        }

        DB::beginTransaction();
    }

    protected function commitDatabaseTransaction() : void
    {
        if (!$this->hasDatabaseTransactions()) {
            return;
        }

        DB::commit();
    }

    protected function rollBackDatabaseTransaction() : void
    {
        if (!$this->hasDatabaseTransactions()) {
            return;
        }

        DB::rollBack();
    }

    protected function wrapInDatabaseTransaction(Closure $callback) : mixed
    {
        if (!$this->hasDatabaseTransactions()) {
            return $callback();
        }

        /** @phpstan-ignore-next-line */
        return DB::transaction($callback);
    }

}
