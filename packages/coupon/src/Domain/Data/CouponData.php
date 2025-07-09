<?php

namespace RedJasmine\Coupon\Domain\Data;

use RedJasmine\Coupon\Domain\Models\Enums\CouponStatusEnum;
use RedJasmine\Coupon\Domain\Models\Enums\DiscountTypeEnum;
use RedJasmine\Coupon\Domain\Models\Enums\IssueStrategyEnum;
use RedJasmine\Coupon\Domain\Models\Enums\ThresholdTypeEnum;
use RedJasmine\Coupon\Domain\Models\Enums\ValidityTypeEnum;
use RedJasmine\Coupon\Domain\Models\ValueObjects\CollectRule;
use RedJasmine\Coupon\Domain\Models\ValueObjects\DiscountRule;
use RedJasmine\Coupon\Domain\Models\ValueObjects\UsageRule;
use RedJasmine\Coupon\Domain\Models\ValueObjects\ValidityRule;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Data\Data;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;

class CouponData extends Data
{
    public ?int $id = null;

    public UserInterface $owner;

    public string $name;

    public ?string $description = null;

    public ?string $image = null;

    #[WithCast(EnumCast::class, CouponStatusEnum::class)]
    public CouponStatusEnum $status = CouponStatusEnum::DRAFT;

    #[WithCast(EnumCast::class, DiscountTypeEnum::class)]
    public DiscountTypeEnum $discountType = DiscountTypeEnum::PERCENTAGE;

    public float $discountValue = 0;

    public ?float $maxDiscountAmount = null;

    public bool $isLadder = false;

    public ?array $ladderRules = null;

    #[WithCast(EnumCast::class, ThresholdTypeEnum::class)]
    public ThresholdTypeEnum $thresholdType = ThresholdTypeEnum::ORDER_AMOUNT;

    public float $thresholdValue = 0;

    public bool $isThresholdRequired = true;

    #[WithCast(EnumCast::class, ValidityTypeEnum::class)]
    public ValidityTypeEnum $validityType = ValidityTypeEnum::ABSOLUTE;

    public ?string $startTime = null;

    public ?string $endTime = null;

    public ?int $relativeDays = null;

    public int $maxUsagePerUser = 1;

    public ?int $maxUsageTotal = null;

    public ?UsageRule $usageRule = null;

    public ?CollectRule $collectRule = null;

    public UserInterface $costBearer;


    #[WithCast(EnumCast::class, IssueStrategyEnum::class)]
    public IssueStrategyEnum $issueStrategy = IssueStrategyEnum::MANUAL;

    public ?int $totalIssueLimit = null;

    public int $currentIssueCount = 0;

    public int $sort = 0;

    public ?string $remark = null;

    public ?string $link = null;

    public array $tags = [];

    public ?string $extendData = null;
} 