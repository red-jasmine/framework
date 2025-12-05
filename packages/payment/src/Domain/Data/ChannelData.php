<?php

namespace RedJasmine\Payment\Domain\Data;

use RedJasmine\Payment\Domain\Models\Enums\ChannelStatusEnum;
use RedJasmine\Support\Foundation\Data\Data;

class ChannelData extends Data
{

    public string $code;

    public string $name;

    public ?string $remarks = null;

    public ChannelStatusEnum $status = ChannelStatusEnum::ENABLE;


}
