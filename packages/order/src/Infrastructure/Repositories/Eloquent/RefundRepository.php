<?php

namespace RedJasmine\Order\Infrastructure\Repositories\Eloquent;

use RedJasmine\Order\Domain\Models\Refund;
use RedJasmine\Order\Domain\Repositories\RefundRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Repository;

class RefundRepository extends Repository implements RefundRepositoryInterface
{

    protected static string $modelClass = Refund::class;

    public function findByNo(string $no) : Refund
    {
        return static::$modelClass::where('refund_no', $no)->firstOrFail();
    }


}
