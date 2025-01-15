<?php

namespace RedJasmine\Payment\Infrastructure\Repositories\Eloquent;

use RedJasmine\Payment\Domain\Models\ChannelMerchant;
use RedJasmine\Payment\Domain\Repositories\ChannelMerchantRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Eloquent\EloquentRepository;

class ChannelMerchantRepository extends EloquentRepository implements ChannelMerchantRepositoryInterface
{

    protected static string $eloquentModelClass = ChannelMerchant::class;


}
