<?php

namespace RedJasmine\Promotion\Domain\Data;

use RedJasmine\Promotion\Domain\Models\Enums\SkuStatusEnum;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Data\Data;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;

class ActivitySkuData extends Data
{
    public UserInterface $owner;
    public int $activityId;
    public int $productId;
    public int $skuId;
    public int $activityProductId;
    
    public ?string $propertiesName = null;
    public ?string $image = null;
    public float $originalPrice;
    
    public ?float $activityPrice = null;
    public ?float $discountRate = null;
    public ?int $activityStock = null;
    public ?int $userPurchaseLimit = null;
    
    #[WithCast(EnumCast::class, SkuStatusEnum::class)]
    public SkuStatusEnum $status = SkuStatusEnum::ACTIVE;
    
    public bool $isShow = true;
}