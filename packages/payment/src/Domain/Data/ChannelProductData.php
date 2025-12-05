<?php

namespace RedJasmine\Payment\Domain\Data;

use RedJasmine\Payment\Domain\Models\Enums\ChannelProductStatusEnum;
use RedJasmine\Payment\Domain\Models\Enums\ChannelProductTypeEnum;
use RedJasmine\Support\Foundation\Data\Data;

class ChannelProductData extends Data
{

    public ChannelProductTypeEnum $type = ChannelProductTypeEnum::PAYMENT;

    public string $channelCode;

    public string $code;

    public string $name;
    /*
     * 网关名称
     */
    public ?string $gateway;


    public ?string $remarks = null;

    public ChannelProductStatusEnum $status = ChannelProductStatusEnum::ENABLE;


    /**
     * 渠道支付模式
     * @var ChannelProductModeData[]
     */
    public array $modes = [];


}
