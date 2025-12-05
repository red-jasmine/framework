<?php

namespace RedJasmine\Promotion\Domain\Data;

use Cknow\Money\Money;
use RedJasmine\Promotion\Domain\Models\Enums\ActivityProductStatusEnum;
use RedJasmine\Support\Domain\Contracts\UserInterface;
use RedJasmine\Support\Foundation\Data\Data;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;

class ActivityProductData extends Data
{

    public int           $activityId;
    public UserInterface $seller;
    public string        $productType;
    public int           $productId;


    public string  $title;
    public ?string $image = null;

    public Money $originalPrice;
    public Money $activityPrice;

    // 活动库存设置
    /**
     * 是否统一库存
     *
     * @var boolean
     */
    public bool $isUnifiedStock = false;


    // 单用户限购数量
    public ?int $userPurchaseLimit = null;


    public ?\DateTime $startTime = null;
    public ?\DateTime $endTime   = null;

    #[WithCast(EnumCast::class, ActivityProductStatusEnum::class)]
    public ActivityProductStatusEnum $status = ActivityProductStatusEnum::PENDING;

    public bool $isShow = true;
}