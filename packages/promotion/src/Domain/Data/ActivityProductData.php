<?php

namespace RedJasmine\Promotion\Domain\Data;

use RedJasmine\Promotion\Domain\Models\Enums\PriceSettingModeEnum;
use RedJasmine\Promotion\Domain\Models\Enums\ProductStatusEnum;
use RedJasmine\Promotion\Domain\Models\Enums\SkuParticipationModeEnum;
use RedJasmine\Promotion\Domain\Models\Enums\StockManagementModeEnum;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Data\Data;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;

class ActivityProductData extends Data
{
    public UserInterface $owner;
    public int $activityId;
    public int $productId;
    
    public string $sellerType;
    public string $sellerId;
    
    public string $title;
    public ?string $image = null;
    public float $originalPrice;
    public int $sales = 0;
    
    public ?float $activityPrice = null;
    public ?float $discountRate = null;
    public ?int $activityStock = null;
    public ?int $userPurchaseLimit = null;
    
    #[WithCast(EnumCast::class, SkuParticipationModeEnum::class)]
    public SkuParticipationModeEnum $skuParticipationMode = SkuParticipationModeEnum::ALL_SKUS;
    
    #[WithCast(EnumCast::class, PriceSettingModeEnum::class)]
    public PriceSettingModeEnum $priceSettingMode = PriceSettingModeEnum::UNIFIED;
    
    #[WithCast(EnumCast::class, StockManagementModeEnum::class)]
    public StockManagementModeEnum $stockManagementMode = StockManagementModeEnum::UNIFIED;
    
    public ?\DateTime $startTime = null;
    public ?\DateTime $endTime = null;
    
    #[WithCast(EnumCast::class, ProductStatusEnum::class)]
    public ProductStatusEnum $status = ProductStatusEnum::PENDING;
    
    public bool $isShow = true;
}