<?php

namespace RedJasmine\Payment\Infrastructure\Repositories;

use RedJasmine\Payment\Domain\Models\Transfer;
use RedJasmine\Payment\Domain\Repositories\TransferRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Repository;

class TransferRepository extends Repository implements TransferRepositoryInterface
{

    protected static string $modelClass = Transfer::class;

    public function findByNo(string $no) : ?Transfer
    {
        return static::$modelClass::where('transfer_no', $no)->first();
    }


}

