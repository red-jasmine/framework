<?php

namespace RedJasmine\Payment\Infrastructure\Repositories\Eloquent;

use RedJasmine\Payment\Domain\Models\Refund;
use RedJasmine\Payment\Domain\Repositories\RefundRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Eloquent\EloquentRepository;

class RefundRepository extends EloquentRepository implements RefundRepositoryInterface
{

    protected static string $eloquentModelClass = Refund::class;

    public function findByNo(string $no) : ?Refund
    {
        return static::$eloquentModelClass::where('refund_no', $no)->first();
    }


}
