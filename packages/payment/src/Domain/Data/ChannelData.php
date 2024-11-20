<?php

namespace RedJasmine\Payment\Domain\Data;

use RedJasmine\Payment\Domain\Models\Enums\ChannelAppStatusEnum;
use RedJasmine\Payment\Domain\Models\Enums\ChannelProductStatusEnum;
use RedJasmine\Payment\Domain\Models\Enums\ChannelStatusEnum;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Data\Data;

class ChannelData extends Data
{

    public string $channel;


    public string $name;


    public ?string $remarks = null;

    public ChannelStatusEnum $status = ChannelStatusEnum::ENABLE;


}
