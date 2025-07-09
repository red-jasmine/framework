<?php

namespace RedJasmine\Coupon\Domain\Models\ValueObjects;

use RedJasmine\Coupon\Domain\Models\Enums\DiscountTypeEnum;
use RedJasmine\Support\Domain\Models\ValueObjects\ValueObject;

class LadderRule extends ValueObject
{
    /**
     * 门槛金额
     */
    public float $thresholdAmount;

    /**
     * 优惠金额
     */
    public float $discountAmount;

    /**
     * 优惠类型
     */
    public DiscountTypeEnum $discountType;

    /**
     * 检查是否适用于指定金额
     */
    public function isApplicableFor(float $amount): bool
    {
        return $amount >= $this->thresholdAmount;
    }

    /**
     * 计算优惠金额
     */
    public function calculateDiscount(float $amount): float
    {
        if (!$this->isApplicableFor($amount)) {
            return 0;
        }

        return match ($this->discountType) {
            DiscountTypeEnum::FIXED_AMOUNT => $this->discountAmount,
            DiscountTypeEnum::PERCENTAGE => $amount * ($this->discountAmount / 100),
        };
    }

    /**
     * 获取显示文案
     */
    public function getDisplayText(): string
    {
        $discountText = match ($this->discountType) {
            DiscountTypeEnum::FIXED_AMOUNT => "减{$this->discountAmount}元",
            DiscountTypeEnum::PERCENTAGE => "打{$this->discountAmount}折",
        };

        return "满{$this->thresholdAmount}元{$discountText}";
    }

    public function equals(object $other): bool
    {
        if (!$other instanceof self) {
            return false;
        }

        return $this->thresholdAmount === $other->thresholdAmount
            && $this->discountAmount === $other->discountAmount
            && $this->discountType === $other->discountType;
    }

    public function hashCode(): int
    {
        return crc32($this->thresholdAmount . $this->discountAmount . $this->discountType->value);
    }
} 