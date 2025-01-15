<?php

namespace RedJasmine\Payment\Domain\Data;

use RedJasmine\Payment\Domain\Models\Enums\ChannelMerchantStatusEnum;
use RedJasmine\Payment\Domain\Models\Enums\MerchantTypeEnum;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Data\Data;

class ChannelMerchantData extends Data
{

    public UserInterface $owner;
    public string        $channelCode;
    public string        $channelMerchantId;
    public string        $channelMerchantName;

    public MerchantTypeEnum          $type      = MerchantTypeEnum::GENERAL;
    public ChannelMerchantStatusEnum $status    = ChannelMerchantStatusEnum::ENABLE;
    public bool                      $isSandbox = false;
    public ?string                   $remarks   = null;


}
