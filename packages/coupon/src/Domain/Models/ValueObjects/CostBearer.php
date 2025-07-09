<?php

namespace RedJasmine\Coupon\Domain\Models\ValueObjects;

use RedJasmine\Coupon\Domain\Models\Enums\CostBearerTypeEnum;
use RedJasmine\Support\Domain\Models\ValueObjects\ValueObject;

class CostBearer extends ValueObject
{
    /**
     * 承担方类型
     */
    public CostBearerTypeEnum $type;

    /**
     * 承担方ID
     */
    public string $id;

    /**
     * 承担方名称
     */
    public string $name;

    /**
     * 获取承担方信息
     */
    public function getInfo(): array
    {
        return [
            'type' => $this->type->value,
            'id' => $this->id,
            'name' => $this->name,
        ];
    }

    /**
     * 获取显示名称
     */
    public function getDisplayName(): string
    {
        return "[{$this->type->getLabel()}] {$this->name}";
    }

    /**
     * 检查是否为平台承担
     */
    public function isPlatform(): bool
    {
        return $this->type === CostBearerTypeEnum::PLATFORM;
    }

    /**
     * 检查是否为商家承担
     */
    public function isMerchant(): bool
    {
        return $this->type === CostBearerTypeEnum::MERCHANT;
    }

    /**
     * 检查是否为主播承担
     */
    public function isBroadcaster(): bool
    {
        return $this->type === CostBearerTypeEnum::BROADCASTER;
    }

    public function equals(object $other): bool
    {
        if (!$other instanceof self) {
            return false;
        }

        return $this->type === $other->type
            && $this->id === $other->id
            && $this->name === $other->name;
    }

    public function hashCode(): int
    {
        return crc32($this->type->value . $this->id . $this->name);
    }
} 