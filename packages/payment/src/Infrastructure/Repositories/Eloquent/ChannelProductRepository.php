<?php

namespace RedJasmine\Payment\Infrastructure\Repositories\Eloquent;

use RedJasmine\Payment\Domain\Models\PaymentChannelProduct;
use RedJasmine\Payment\Domain\Repositories\ChannelProductRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Eloquent\EloquentRepository;

class ChannelProductRepository extends EloquentRepository implements ChannelProductRepositoryInterface
{

    protected static string $eloquentModelClass = PaymentChannelProduct::class;

    public function findByCode(string $channelCode, string $code) : ?PaymentChannelProduct
    {
        return static::$eloquentModelClass::where('code', $code)
                                          ->where('channel_code', $channelCode)
                                          ->firstOrFail();
    }


}
