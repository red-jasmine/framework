<?php

namespace RedJasmine\Payment\Domain\Data;

use RedJasmine\Payment\Domain\Models\Enums\NotifyBusinessTypeEnum;
use RedJasmine\Support\Data\Data;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;

class NotifyData extends Data
{

    public int $merchantId;

    public int $merchantAppId;

    /**
     * 通知类型
     * @var string
     */
    public string $notifyType;

    #[WithCast(EnumCast::class, NotifyBusinessTypeEnum::class)]
    public NotifyBusinessTypeEnum $businessType;

    public string $businessNo;

    public string $url;

    public array $body;
}
