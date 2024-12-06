<?php

namespace RedJasmine\Payment\Infrastructure\Repositories\Eloquent;

use RedJasmine\Payment\Domain\Models\Platform;
use RedJasmine\Payment\Domain\Repositories\PlatformRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Eloquent\EloquentRepository;

class PlatformRepository extends EloquentRepository implements PlatformRepositoryInterface
{

    protected static string $eloquentModelClass = Platform::class;

    public function findByCode(string $code) : ?Platform
    {
        return static::$eloquentModelClass::where('code', $code)->firstOrFail();
    }


}
