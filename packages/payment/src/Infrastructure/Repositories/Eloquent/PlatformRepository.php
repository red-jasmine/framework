<?php

namespace RedJasmine\Payment\Infrastructure\Repositories\Eloquent;

use RedJasmine\Payment\Domain\Models\PaymentPlatform;
use RedJasmine\Payment\Domain\Repositories\PlatformRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Eloquent\EloquentRepository;

class PlatformRepository extends EloquentRepository implements PlatformRepositoryInterface
{

    protected static string $eloquentModelClass = PaymentPlatform::class;

    public function findByCode(string $code) : ?PaymentPlatform
    {
        return static::$eloquentModelClass::where('code', $code)->findOrFail();
    }


}
