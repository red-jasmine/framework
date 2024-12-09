<?php

namespace RedJasmine\Payment\Domain\Data;

use RedJasmine\Payment\Domain\Models\Enums\ModeStatusEnum;
use RedJasmine\Support\Data\Data;

class ChannelProductModeData extends Data
{

    /**
     * 支付场景
     * @var string
     */
    public string $sceneCode;


    /**
     * 支付 平台
     * @var string
     */
    public string $methodCode;

    public ModeStatusEnum $status = ModeStatusEnum::ENABLE;


}
