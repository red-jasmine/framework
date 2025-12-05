<?php

namespace RedJasmine\Promotion\Domain\Data;

use Cknow\Money\Money;
use RedJasmine\Promotion\Domain\Models\Enums\SkuStatusEnum;
use RedJasmine\Support\Foundation\Data\Data;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;

class ActivitySkuData extends Data
{

    public int     $skuId;
    public ?string $title = null;
    public ?string $image = null;
    public Money   $originalPrice;
    public Money   $activityPrice;

    public int $activityStock;

    #[WithCast(EnumCast::class, SkuStatusEnum::class)]
    public SkuStatusEnum $status = SkuStatusEnum::ACTIVE;

    public bool $isShow = true;
}