<?php

namespace RedJasmine\Payment\Infrastructure\Repositories\Eloquent;

use RedJasmine\Payment\Domain\Models\Channel;
use RedJasmine\Payment\Domain\Repositories\ChannelRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Eloquent\EloquentRepository;

class ChannelRepository extends EloquentRepository implements ChannelRepositoryInterface
{

    protected static string $eloquentModelClass = Channel::class;

    public function findByCode(string $code) : ?Channel
    {
        return static::$eloquentModelClass::where('code', $code)->firstOrFail();
    }


}
