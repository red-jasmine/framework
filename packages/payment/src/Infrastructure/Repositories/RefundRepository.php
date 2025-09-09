<?php

namespace RedJasmine\Payment\Infrastructure\Repositories;

use RedJasmine\Payment\Domain\Models\Refund;
use RedJasmine\Payment\Domain\Repositories\RefundRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Repository;

class RefundRepository extends Repository implements RefundRepositoryInterface
{

    protected static string $modelClass = Refund::class;

    public function findByNo(string $no) : ?Refund
    {
        return static::$modelClass::where('refund_no', $no)->first();
    }


}

