<?php

namespace RedJasmine\Payment\Domain\Data;

use RedJasmine\Payment\Domain\Models\Enums\ChannelProductStatusEnum;
use RedJasmine\Support\Data\Data;

class ChannelProductData extends Data
{

    public string $channelCode;

    public string $code;

    public string $name;

    public float $rate = 0;

    public ChannelProductStatusEnum $status = ChannelProductStatusEnum::ENABLE;


}
