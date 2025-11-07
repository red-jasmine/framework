<?php

namespace RedJasmine\Vip\Domain\Data;

use RedJasmine\Money\Data\Money;
use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Domain\Data\Enums\TimeUnitEnum;

use RedJasmine\Vip\Domain\Models\Enums\VipProductStatusEnum;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;

class VipProductData extends Data
{
    public string $biz;

    public string $type;

    public string $name;

    public int    $stock = 10000000;

    public Money $price;

    #[WithCast(EnumCast::class, VipProductStatusEnum::class)]
    public VipProductStatusEnum $status = VipProductStatusEnum::ON_SALE;

    #[WithCast(EnumCast::class, TimeUnitEnum::class)]
    public TimeUnitEnum $timeUnit  = TimeUnitEnum::MONTH; // 时间单位
    public int          $timeValue = 1;


}