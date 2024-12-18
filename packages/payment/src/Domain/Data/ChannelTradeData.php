<?php

namespace RedJasmine\Payment\Domain\Data;

use RedJasmine\Payment\Domain\Models\ValueObjects\Money;
use RedJasmine\Support\Data\Data;

class ChannelTradeData extends Data
{
    public int     $merchantAppId;
    public int     $merchantId;
    public int     $id;
    public ?Money  $amount;
    public ?string $channelCode;
    public ?string $channelProductCode;
    public ?string $channelAppId;
    public ?string $channelMerchantId;
    public ?string $channelTradeNo;
    public ?string $sceneCode;
    public ?string $methodCode;


}
