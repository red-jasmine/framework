<?php

namespace RedJasmine\Promotion\Domain\Data;

use RedJasmine\Promotion\Domain\Models\Enums\PriceSettingModeEnum;
use RedJasmine\Promotion\Domain\Models\Enums\ActivityProductStatusEnum;
use RedJasmine\Promotion\Domain\Models\Enums\SkuParticipationModeEnum;
use RedJasmine\Promotion\Domain\Models\Enums\StockManagementModeEnum;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Data\Data;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;

class ActivityProductData extends Data
{

    public int $activityId;
    public UserInterface $seller;
    public string $productType;
    public int $productId;
 
    
    public string $title;
    public ?string $image = null;
    public float $originalPrice;
  
    

    public ?float $activityPrice = null;
    public ?float $discountRate = null;
    
    
    // 活动库存设置
    /**
     * 是否统一库存
     *
     * @var boolean
     */
    public bool $isUnifiedStock = false;
    
    // 活动总库存
    public ?int $activityStock = null;
    // 可用库存
    public ?int $stock = null;
   
    // 单用户限购数量
    public ?int $userPurchaseLimit = null;
    
    #[WithCast(EnumCast::class, SkuParticipationModeEnum::class)]
    public SkuParticipationModeEnum $skuParticipationMode = SkuParticipationModeEnum::ALL_SKUS;
    
    #[WithCast(EnumCast::class, PriceSettingModeEnum::class)]
    public PriceSettingModeEnum $priceSettingMode = PriceSettingModeEnum::UNIFIED;
    
    #[WithCast(EnumCast::class, StockManagementModeEnum::class)]
    public StockManagementModeEnum $stockManagementMode = StockManagementModeEnum::UNIFIED;
    
    public ?\DateTime $startTime = null;
    public ?\DateTime $endTime = null;
    
    #[WithCast(EnumCast::class, ActivityProductStatusEnum::class)]
    public ActivityProductStatusEnum $status = ActivityProductStatusEnum::PENDING;
    
    public bool $isShow = true;
}