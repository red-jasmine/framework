<?php

namespace RedJasmine\Payment\Infrastructure\Repositories\Eloquent;

use RedJasmine\Payment\Domain\Models\Method;
use RedJasmine\Payment\Domain\Repositories\MethodRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Repository;

class MethodRepository extends Repository implements MethodRepositoryInterface
{

    protected static string $modelClass = Method::class;

    public function findByCode(string $code) : ?Method
    {
        return static::$modelClass::where('code', $code)->firstOrFail();
    }


}
