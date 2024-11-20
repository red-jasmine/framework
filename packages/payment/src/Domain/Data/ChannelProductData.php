<?php

namespace RedJasmine\Payment\Domain\Data;

use RedJasmine\Payment\Domain\Models\Enums\ChannelProductStatusEnum;
use RedJasmine\Support\Data\Data;

class ChannelProductData extends Data
{

    public int $channelId;

    public string $code;

    public string $name;

    public ChannelProductStatusEnum $status = ChannelProductStatusEnum::ENABLE;


}
