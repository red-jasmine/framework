<?php

namespace RedJasmine\Coupon\Domain\Models\ValueObjects;

use RedJasmine\Coupon\Domain\Models\Enums\RuleObjectTypeEnum;
use RedJasmine\Coupon\Domain\Models\Enums\RuleTypeEnum;
use RedJasmine\Support\Domain\Models\ValueObjects\ValueObject;

class RuleItem extends RuleValue
{


    /**
     * 规则类型
     * exclude 排除
     * include 包含
     */
    public RuleTypeEnum $ruleType;

    /**
     * 检查是否匹配
     */
    public function matches(RuleObjectTypeEnum $objectType, string $objectValue) : bool
    {
        if ($objectType !== $this->objectType) {
            return false;
        }

        if ($this->objectType === RuleObjectTypeEnum::USER_RECEIVE_LIMIT) {
            return bccomp($objectValue, $this->objectValue, 0) < 0;
        }


        return $this->objectValue === $objectValue;
    }


    public function equals(object $other) : bool
    {
        if (!$other instanceof self) {
            return false;
        }

        return $this->objectType === $other->objectType
               && $this->ruleType === $other->ruleType
               && $this->objectValue === $other->objectValue;
    }

} 