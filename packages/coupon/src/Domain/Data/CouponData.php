<?php

namespace RedJasmine\Coupon\Domain\Data;

use RedJasmine\Coupon\Domain\Models\Enums\CouponStatusEnum;
use RedJasmine\Coupon\Domain\Models\Enums\DiscountAmountTypeEnum;
use RedJasmine\Coupon\Domain\Models\Enums\DiscountTargetEnum;
use RedJasmine\Coupon\Domain\Models\Enums\IssueStrategyEnum;
use RedJasmine\Coupon\Domain\Models\Enums\ThresholdTypeEnum;
use RedJasmine\Coupon\Domain\Models\Enums\ValidityTypeEnum;
use RedJasmine\Coupon\Domain\Models\ValueObjects\CollectRule;
use RedJasmine\Coupon\Domain\Models\ValueObjects\RuleItem;
use RedJasmine\Coupon\Domain\Models\ValueObjects\UsageRule;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Domain\Data\Enums\TimeUnitEnum;
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
     * 总数量
     * @var int
     */
    public int $totalQuantity;


    #[WithCast(EnumCast::class, CouponStatusEnum::class)]
    public CouponStatusEnum $status = CouponStatusEnum::DRAFT;

    /**
     * 优惠目标
     * @var DiscountTargetEnum
     */
    #[WithCast(EnumCast::class, DiscountTargetEnum::class)]
    public DiscountTargetEnum $discountTarget = DiscountTargetEnum::ORDER_AMOUNT;

    /**
     * 优惠金额类型
     * @var DiscountAmountTypeEnum
     */
    #[WithCast(EnumCast::class, DiscountAmountTypeEnum::class)]
    public DiscountAmountTypeEnum $discountAmountType = DiscountAmountTypeEnum::PERCENTAGE;

    /**
     * 优惠金额值
     * @var float
     */
    public float $discountAmountValue = 0;
    /**
     * 门槛 类型
     * @var ThresholdTypeEnum
     */
    public ThresholdTypeEnum $thresholdType = ThresholdTypeEnum::AMOUNT;

    /**
     * 门槛值
     * @var float
     */
    public float $thresholdValue = 0;


    /**
     * 最大优惠金额
     * @var float
     */
    public float $maxDiscountAmount = 0;


    // 时间限制
    #[WithCast(EnumCast::class, ValidityTypeEnum::class)]
    public ValidityTypeEnum $validityType = ValidityTypeEnum::ABSOLUTE;
    // 绝对生效时间
    public ?string $validityStartTime = null;
    public ?string $validityEndTime   = null;

    // 生效时间
    #[WithCast(EnumCast::class, TimeUnitEnum::class)]
    public ?TimeUnitEnum $delayedEffectiveTimeType;
    public ?int          $delayedEffectiveTimeValue = null;
    // 相对有效期
    #[WithCast(EnumCast::class, TimeUnitEnum::class)]
    public ?TimeUnitEnum $validityTimeType;
    public ?int          $validityTimeValue = null;


    // 使用规则
    /**
     * @var RuleItem[]
     */
    public array $usageRules = [];


    /**
     * 领取规则
     * @var RuleItem[]
     */
    public array $receiveRules = [];


    /**
     * 成本承担方
     * @var UserInterface
     */
    public UserInterface $costBearer;


    public int $sort = 0;

    public ?string $remark = null;

}