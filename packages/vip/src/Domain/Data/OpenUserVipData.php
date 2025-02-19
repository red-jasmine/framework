<?php

namespace RedJasmine\Vip\Domain\Data;

use Illuminate\Support\Carbon;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Domain\Data\Enums\TimeUnitEnum;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Casts\EnumCast;

class OpenUserVipData extends Data
{

    public UserInterface $owner;
    public string        $appId;
    public string        $type;
    #[WithCast(EnumCast::class, TimeUnitEnum::class)]
    public TimeUnitEnum  $timeUnit  = TimeUnitEnum::MONTH; // 时间单位
    public int           $timeValue = 1;

    // 购买支付类型
    public ?string $paymentType;
    public ?string $paymentId;


}