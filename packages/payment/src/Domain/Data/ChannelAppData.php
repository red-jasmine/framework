<?php

namespace RedJasmine\Payment\Domain\Data;

use RedJasmine\Payment\Domain\Models\Enums\ChannelAppStatusEnum;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Data\Data;

class ChannelAppData extends Data
{

    public UserInterface $owner;


    public string $channel;


    public ChannelAppStatusEnum $status = ChannelAppStatusEnum::ENABLE;

    public string $channelMerchantId;

    public string $channelAppId;

    public string $channelPublicKey;

    public string $channelAppPublicKey;

    public string $channelAppPrivateKey;


}
