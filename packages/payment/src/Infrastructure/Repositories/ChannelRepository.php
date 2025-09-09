<?php

namespace RedJasmine\Payment\Infrastructure\Repositories;

use RedJasmine\Payment\Domain\Models\Channel;
use RedJasmine\Payment\Domain\Repositories\ChannelRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Repository;

class ChannelRepository extends Repository implements ChannelRepositoryInterface
{

    protected static string $modelClass = Channel::class;

    public function findByCode(string $code) : ?Channel
    {
        return static::$modelClass::where('code', $code)->firstOrFail();
    }


}

