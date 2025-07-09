<?php

namespace RedJasmine\Coupon\Domain\Models\ValueObjects;

use RedJasmine\Coupon\Domain\Models\Enums\RuleObjectTypeEnum;
use RedJasmine\Coupon\Domain\Models\Enums\RuleTypeEnum;
use RedJasmine\Support\Domain\Models\ValueObjects\ValueObject;

class RuleItem extends ValueObject
{
    /**
     * 规则对象类型
     */
    public RuleObjectTypeEnum $objectType;

    /**
     * 规则类型
     */
    public RuleTypeEnum $ruleType;

    /**
     * 对象值
     */
    public string $objectValue;

    /**
     * 检查是否匹配
     */
    public function matches(RuleObjectTypeEnum $objectType, string $objectValue): bool
    {
        return $this->objectType === $objectType && $this->objectValue === $objectValue;
    }

    /**
     * 获取显示名称
     */
    public function getDisplayName(): string
    {
        return "[{$this->objectType->getLabel()}] {$this->objectValue}";
    }

    public function equals(object $other): bool
    {
        if (!$other instanceof self) {
            return false;
        }

        return $this->objectType === $other->objectType
            && $this->ruleType === $other->ruleType
            && $this->objectValue === $other->objectValue;
    }

    public function hashCode(): int
    {
        return crc32($this->objectType->value . $this->ruleType->value . $this->objectValue);
    }
} 