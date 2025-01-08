<?php

namespace RedJasmine\Payment\Infrastructure\Repositories\Eloquent;

use RedJasmine\Payment\Domain\Models\Transfer;
use RedJasmine\Payment\Domain\Repositories\TransferRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Eloquent\EloquentRepository;

class TransferRepository extends EloquentRepository implements TransferRepositoryInterface
{

    protected static string $eloquentModelClass = Transfer::class;

    public function findByNo(string $no) : ?Transfer
    {
        return static::$eloquentModelClass::where('transfer_no', $no)->first();
    }


}
