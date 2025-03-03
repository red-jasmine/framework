<?php

namespace RedJasmine\Payment\Domain\Models\ValueObjects;

use RedJasmine\Payment\Domain\Models\Enums\ClientTypeEnum;
use RedJasmine\Support\Data\Data;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;

class Client extends Data
{
    /**
     * 客户端类型
     * @var ClientTypeEnum|null
     */
    #[WithCast(EnumCast::class, ClientTypeEnum::class)]
    public ?ClientTypeEnum $type;

    // 小程序、H5 环境 如  微信、支付宝、抖音等
    public ?string $platform;

    // 平台的用户信息


    public ?string $name;

    public ?string $version;

    public ?string $ip;

    public ?string $agent;


}
