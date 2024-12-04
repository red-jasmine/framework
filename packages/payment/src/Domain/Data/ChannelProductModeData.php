<?php

namespace RedJasmine\Payment\Domain\Data;

use RedJasmine\Payment\Domain\Models\Enums\ModeStatusEnum;
use RedJasmine\Support\Data\Data;

class ChannelProductModeData extends Data
{

    /**
     * 支付方式
     * @var string
     */
    public string $methodCode;


    /**
     * 支付平台
     * @var string
     */
    public string $platFromCode;

    public ModeStatusEnum $status = ModeStatusEnum::ENABLE;


}
