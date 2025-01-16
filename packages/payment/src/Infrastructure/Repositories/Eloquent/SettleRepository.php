<?php

namespace RedJasmine\Payment\Infrastructure\Repositories\Eloquent;

use RedJasmine\Payment\Domain\Models\Settle;
use RedJasmine\Payment\Domain\Repositories\SettleRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Eloquent\EloquentRepository;

class SettleRepository extends EloquentRepository implements SettleRepositoryInterface
{

    protected static string $eloquentModelClass = Settle::class;

    public function findByNo(string $no) : ?Settle
    {
        return static::$eloquentModelClass::where('settle_no', $no)->first();
    }


}
