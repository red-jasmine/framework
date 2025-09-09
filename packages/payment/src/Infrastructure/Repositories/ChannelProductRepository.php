<?php

namespace RedJasmine\Payment\Infrastructure\Repositories;

use RedJasmine\Payment\Domain\Models\ChannelProduct;
use RedJasmine\Payment\Domain\Repositories\ChannelProductRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Repository;

class ChannelProductRepository extends Repository implements ChannelProductRepositoryInterface
{

    protected static string $modelClass = ChannelProduct::class;

    public function findByCode(string $channelCode, string $code) : ?ChannelProduct
    {
        return static::$modelClass::where('code', $code)
                                  ->where('channel_code', $channelCode)
                                  ->firstOrFail();
    }


}

