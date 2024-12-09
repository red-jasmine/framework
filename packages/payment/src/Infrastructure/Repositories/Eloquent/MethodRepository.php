<?php

namespace RedJasmine\Payment\Infrastructure\Repositories\Eloquent;

use RedJasmine\Payment\Domain\Models\Method;
use RedJasmine\Payment\Domain\Repositories\MethodRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Eloquent\EloquentRepository;

class MethodRepository extends EloquentRepository implements MethodRepositoryInterface
{

    protected static string $eloquentModelClass = Method::class;

    public function findByCode(string $code) : ?Method
    {
        return static::$eloquentModelClass::where('code', $code)->firstOrFail();
    }


}
