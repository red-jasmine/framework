<?php

namespace RedJasmine\Payment\Infrastructure\Repositories\Eloquent;

use RedJasmine\Payment\Domain\Models\Notify;
use RedJasmine\Payment\Domain\Repositories\NotifyRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Repository;

class NotifyRepository extends Repository implements NotifyRepositoryInterface
{

    protected static string $modelClass = Notify::class;

    public function findByNo(string $no) : ?Notify
    {
        return static::$modelClass::where('notify_no', $no)->first();
    }


}
