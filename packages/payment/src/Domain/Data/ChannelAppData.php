<?php

namespace RedJasmine\Payment\Domain\Data;

use RedJasmine\Payment\Domain\Models\Enums\ChannelAppStatusEnum;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Data\Data;

class ChannelAppData extends Data
{

    public UserInterface $owner;


    public int $channelId;


    public ChannelAppStatusEnum $status = ChannelAppStatusEnum::ENABLE;


    public string  $channelAppId;
    public ?string $channelMerchantId;
    public ?string $appName      = null;
    public ?string $merchantName = null;
    public string  $channelPublicKey;

    public string $channelAppPublicKey;

    public string $channelAppPrivateKey;

    public string  $feeRate = '0';
    public ?string $remarks = null;


    /**
     * 开通产品
     * @var array
     */
    public array $products = [];


}
