<?php

namespace RedJasmine\Order\Infrastructure\Repositories\Eloquent;

use DB;
use RedJasmine\Order\Domain\Models\Refund;
use RedJasmine\Order\Domain\Repositories\RefundRepositoryInterface;
use RedJasmine\Support\Exceptions\AbstractException;
use RedJasmine\Support\Infrastructure\Repositories\Eloquent\EloquentRepository;
use Throwable;

class RefundRepository extends EloquentRepository implements RefundRepositoryInterface
{

    protected static string $eloquentModelClass = Refund::class;

    public function findByNo(string $no) : Refund
    {
        return static::$eloquentModelClass::where('refund_no', $no)->firstOrFail();
    }


}
