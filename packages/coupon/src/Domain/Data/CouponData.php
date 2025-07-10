<?php

namespace RedJasmine\Coupon\Domain\Data;

use Illuminate\Support\Carbon;
use RedJasmine\Coupon\Domain\Models\Enums\CouponStatusEnum;
use RedJasmine\Coupon\Domain\Models\Enums\DiscountAmountTypeEnum;
use RedJasmine\Coupon\Domain\Models\Enums\DiscountTargetEnum;
use RedJasmine\Coupon\Domain\Models\Enums\ThresholdTypeEnum;
use RedJasmine\Coupon\Domain\Models\Enums\ValidityTypeEnum;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Domain\Data\Enums\TimeUnitEnum;
use RedJasmine\Support\Domain\Data\TimeConfigData;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;

class CouponData extends Data
{
    public UserInterface $owner;

    public string $name;

    public ?string $description = null;

    public ?string $image = null;

    public bool $isShow = true;

    /**
     * 开始时间
     */
    public ?Carbon $startTime = null;

    /**
     * 发布时间
     * @var Carbon|null
     */
    public ?Carbon $endTime = null;


    #[WithCast(EnumCast::class, CouponStatusEnum::class)]
    public CouponStatusEnum $status = CouponStatusEnum::DRAFT;

    /**
     * 优惠目标
     */
    #[WithCast(EnumCast::class, DiscountTargetEnum::class)]
    public DiscountTargetEnum $discountTarget = DiscountTargetEnum::ORDER_AMOUNT;

    /**
     * 优惠金额类型
     */
    #[WithCast(EnumCast::class, DiscountAmountTypeEnum::class)]
    public DiscountAmountTypeEnum $discountAmountType = DiscountAmountTypeEnum::PERCENTAGE;

    /**
     * 优惠金额值
     */
    public float $discountAmountValue = 0;

    /**
     * 门槛类型
     */
    #[WithCast(EnumCast::class, ThresholdTypeEnum::class)]
    public ThresholdTypeEnum $thresholdType = ThresholdTypeEnum::AMOUNT;

    /**
     * 门槛值
     */
    public float $thresholdValue = 0;

    /**
     * 最大优惠金额
     */
    public float $maxDiscountAmount = 0;

    /**
     * 有效期类型
     */
    #[WithCast(EnumCast::class, ValidityTypeEnum::class)]
    public ValidityTypeEnum $validityType = ValidityTypeEnum::ABSOLUTE;

    /**
     * 绝对生效时间
     */
    public ?Carbon $validityStartTime = null;

    public ?Carbon $validityEndTime = null;


    /**
     * 延迟生效时间
     * @var TimeConfigData|null
     */
    public ?TimeConfigData $delayedEffectiveTime;

    /**
     * 相对有效期
     * @var TimeConfigData|null
     */
    public ?TimeConfigData $validityTime;

    /**
     * 使用规则
     */
    public array $usageRules = [];

    /**
     * 领取规则
     */
    public array $receiveRules = [];

    /**
     * 成本承担方
     */
    public UserInterface $costBearer;

    public int $sort = 0;

    public ?string $remarks = null;

    /**
     * 总数量
     */
    public int $totalQuantity = 0;
}