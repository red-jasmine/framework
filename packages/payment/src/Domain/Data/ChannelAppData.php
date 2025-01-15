<?php

namespace RedJasmine\Payment\Domain\Data;

use RedJasmine\Payment\Domain\Models\Enums\ChannelAppStatusEnum;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Data\Data;

class ChannelAppData extends Data
{


    public int                  $systemChannelMerchantId;
    public string               $channelAppId;
    public string               $appName;
    public ChannelAppStatusEnum $status = ChannelAppStatusEnum::ENABLE;

    public string  $signType = 'RSA2';
    public ?string $channelPublicKey;
    public ?string $channelAppPublicKey;
    public ?string $channelAppPrivateKey;
    /**
     * 是否沙箱
     * @var bool
     */
    public bool $isSandbox = false;


    public ?string $remarks = null;
    /**
     * 开通产品
     * @var array
     */
    public array $products = [];


}
