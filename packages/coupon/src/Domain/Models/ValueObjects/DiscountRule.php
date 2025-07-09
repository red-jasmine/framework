<?php

namespace RedJasmine\Coupon\Domain\Models\ValueObjects;

use Illuminate\Support\Collection;
use RedJasmine\Coupon\Domain\Models\Enums\DiscountTypeEnum;
use RedJasmine\Coupon\Domain\Models\Enums\ThresholdTypeEnum;
use RedJasmine\Support\Domain\Models\ValueObjects\ValueObject;

class DiscountRule extends ValueObject
{
    /**
     * 门槛类型
     */
    public ThresholdTypeEnum $thresholdType;

    /**
     * 门槛金额
     */
    public float $thresholdValue;

    /**
     * 是否需要门槛
     */
    public bool $isThresholdRequired;

    /**
     * 优惠类型
     */
    public DiscountTypeEnum $discountType;

    /**
     * 优惠值
     */
    public float $discountValue;

    /**
     * 最大优惠金额
     */
    public ?float $maxDiscountAmount;

    /**
     * 是否阶梯优惠
     */
    public bool $isLadder;

    /**
     * 阶梯规则列表
     * @var Collection<LadderRule>
     */
    public Collection $ladderRules;

    public function __construct(array $data = [])
    {
        $this->thresholdType = $data['thresholdType'] ?? ThresholdTypeEnum::ORDER_AMOUNT;
        $this->thresholdValue = $data['thresholdValue'] ?? 0;
        $this->isThresholdRequired = $data['isThresholdRequired'] ?? true;
        $this->discountType = $data['discountType'] ?? DiscountTypeEnum::FIXED_AMOUNT;
        $this->discountValue = $data['discountValue'] ?? 0;
        $this->maxDiscountAmount = $data['maxDiscountAmount'] ?? null;
        $this->isLadder = $data['isLadder'] ?? false;
        $this->ladderRules = collect($data['ladderRules'] ?? [])
            ->map(fn($rule) => $rule instanceof LadderRule ? $rule : new LadderRule($rule));
    }

    /**
     * 检查门槛条件
     */
    public function checkThreshold(float $amount): bool
    {
        if (!$this->isThresholdRequired) {
            return true;
        }

        return $amount >= $this->thresholdValue;
    }

    /**
     * 计算优惠金额
     */
    public function calculateDiscount(float $amount): float
    {
        if (!$this->checkThreshold($amount)) {
            return 0;
        }

        if ($this->isLadder && $this->ladderRules->isNotEmpty()) {
            return $this->calculateLadderDiscount($amount);
        }

        return $this->calculateSimpleDiscount($amount);
    }

    /**
     * 计算阶梯优惠
     */
    private function calculateLadderDiscount(float $amount): float
    {
        $applicableLadder = $this->ladderRules
            ->filter(fn(LadderRule $rule) => $rule->isApplicableFor($amount))
            ->sortByDesc('thresholdAmount')
            ->first();

        if (!$applicableLadder) {
            return 0;
        }

        $discount = $applicableLadder->calculateDiscount($amount);

        return $this->applyMaxDiscount($discount);
    }

    /**
     * 计算简单优惠
     */
    private function calculateSimpleDiscount(float $amount): float
    {
        $discount = match ($this->discountType) {
            DiscountTypeEnum::FIXED_AMOUNT => $this->discountValue,
            DiscountTypeEnum::PERCENTAGE => $amount * ($this->discountValue / 100),
        };

        return $this->applyMaxDiscount($discount);
    }

    /**
     * 应用最大优惠限制
     */
    private function applyMaxDiscount(float $discount): float
    {
        if ($this->maxDiscountAmount !== null) {
            return min($discount, $this->maxDiscountAmount);
        }

        return $discount;
    }

    /**
     * 验证规则
     */
    public function validateRule(float $amount): bool
    {
        return $this->checkThreshold($amount) && $this->calculateDiscount($amount) > 0;
    }

    /**
     * 获取显示文案
     */
    public function getDisplayText(): string
    {
        if ($this->isLadder && $this->ladderRules->isNotEmpty()) {
            return $this->ladderRules->map(fn(LadderRule $rule) => $rule->getDisplayText())->implode('，');
        }

        $thresholdText = $this->isThresholdRequired ? "满{$this->thresholdValue}元" : "无门槛";
        $discountText = match ($this->discountType) {
            DiscountTypeEnum::FIXED_AMOUNT => "减{$this->discountValue}元",
            DiscountTypeEnum::PERCENTAGE => "打{$this->discountValue}折",
        };

        $maxText = $this->maxDiscountAmount ? "（最多减{$this->maxDiscountAmount}元）" : '';

        return $thresholdText . $discountText . $maxText;
    }

    public function equals(object $other): bool
    {
        if (!$other instanceof self) {
            return false;
        }

        return $this->thresholdType === $other->thresholdType
            && $this->thresholdValue === $other->thresholdValue
            && $this->isThresholdRequired === $other->isThresholdRequired
            && $this->discountType === $other->discountType
            && $this->discountValue === $other->discountValue
            && $this->maxDiscountAmount === $other->maxDiscountAmount
            && $this->isLadder === $other->isLadder
            && $this->ladderRules->count() === $other->ladderRules->count();
    }

    public function hashCode(): int
    {
        return crc32(serialize([
            $this->thresholdType->value,
            $this->thresholdValue,
            $this->isThresholdRequired,
            $this->discountType->value,
            $this->discountValue,
            $this->maxDiscountAmount,
            $this->isLadder,
            $this->ladderRules->toArray(),
        ]));
    }
} 