<?php

namespace RedJasmine\Coupon\Domain\Models\ValueObjects;

use Illuminate\Support\Collection;
use RedJasmine\Coupon\Domain\Models\Enums\RuleObjectTypeEnum;
use RedJasmine\Coupon\Domain\Models\Enums\RuleTypeEnum;
use RedJasmine\Support\Domain\Models\ValueObjects\ValueObject;

class CollectRule extends ValueObject
{
    /**
     * 规则项集合
     * @var Collection<RuleItem>
     */
    public Collection $items;

    public function __construct(array $items = [])
    {
        $this->items = collect($items)->map(fn($item) => $item instanceof RuleItem ? $item : new RuleItem($item));
    }

    /**
     * 检查是否可以领取
     */
    public function canCollect(array $context): bool
    {
        if ($this->items->isEmpty()) {
            return true;
        }

        // 检查排除规则
        foreach ($this->items->where('ruleType', RuleTypeEnum::EXCLUDE) as $item) {
            if ($this->matchesContext($item, $context)) {
                return false;
            }
        }

        // 检查包含规则
        $includeItems = $this->items->where('ruleType', RuleTypeEnum::INCLUDE);
        if ($includeItems->isNotEmpty()) {
            foreach ($includeItems as $item) {
                if ($this->matchesContext($item, $context)) {
                    return true;
                }
            }
            return false;
        }

        return true;
    }

    /**
     * 匹配上下文
     */
    private function matchesContext(RuleItem $item, array $context): bool
    {
        $key = $item->objectType->value;
        
        if (!isset($context[$key])) {
            return false;
        }

        $values = is_array($context[$key]) ? $context[$key] : [$context[$key]];

        return in_array($item->objectValue, $values);
    }

    /**
     * 添加规则项
     */
    public function addItem(RuleItem $item): self
    {
        $this->items->push($item);
        return $this;
    }

    /**
     * 获取特定类型的规则项
     */
    public function getItemsByType(RuleObjectTypeEnum $objectType): Collection
    {
        return $this->items->where('objectType', $objectType);
    }

    /**
     * 获取包含规则项
     */
    public function getIncludeItems(): Collection
    {
        return $this->items->where('ruleType', RuleTypeEnum::INCLUDE);
    }

    /**
     * 获取排除规则项
     */
    public function getExcludeItems(): Collection
    {
        return $this->items->where('ruleType', RuleTypeEnum::EXCLUDE);
    }

    public function equals(object $other): bool
    {
        if (!$other instanceof self) {
            return false;
        }

        return $this->items->count() === $other->items->count()
            && $this->items->every(fn($item) => $other->items->contains(fn($otherItem) => $item->equals($otherItem)));
    }

    public function hashCode(): int
    {
        return crc32(serialize($this->items->toArray()));
    }
} 