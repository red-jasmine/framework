<?php

namespace RedJasmine\Payment\Infrastructure\Repositories;

use RedJasmine\Payment\Domain\Models\Settle;
use RedJasmine\Payment\Domain\Repositories\SettleRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Repository;

class SettleRepository extends Repository implements SettleRepositoryInterface
{

    protected static string $modelClass = Settle::class;

    public function findByNo(string $no) : ?Settle
    {
        return static::$modelClass::where('settle_no', $no)->first();
    }


}

