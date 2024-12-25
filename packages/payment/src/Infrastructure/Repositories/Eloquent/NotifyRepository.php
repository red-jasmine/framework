<?php

namespace RedJasmine\Payment\Infrastructure\Repositories\Eloquent;

use RedJasmine\Payment\Domain\Models\Notify;
use RedJasmine\Payment\Domain\Repositories\NotifyRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Eloquent\EloquentRepository;

class NotifyRepository extends EloquentRepository implements NotifyRepositoryInterface
{

    protected static string $eloquentModelClass = Notify::class;

    public function findByNo(string $no) : ?Notify
    {
        return static::$eloquentModelClass::where('notify_no', $no)->first();
    }


}
