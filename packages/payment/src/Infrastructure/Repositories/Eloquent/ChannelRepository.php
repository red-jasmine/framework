<?php

namespace RedJasmine\Payment\Infrastructure\Repositories\Eloquent;

use RedJasmine\Payment\Domain\Models\PaymentChannel;
use RedJasmine\Payment\Domain\Repositories\ChannelRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Eloquent\EloquentRepository;

class ChannelRepository extends EloquentRepository implements ChannelRepositoryInterface
{

    protected static string $eloquentModelClass = PaymentChannel::class;

    public function findByCode(string $code) : ?PaymentChannel
    {
        return static::$eloquentModelClass::where('code', $code)->firstOrFail();
    }


}
