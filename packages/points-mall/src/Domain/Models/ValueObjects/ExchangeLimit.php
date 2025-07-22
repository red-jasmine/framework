<?php

namespace RedJasmine\PointsMall\Domain\Models\ValueObjects;

use RedJasmine\Support\Domain\Models\ValueObjects\ValueObject;

class ExchangeLimit extends ValueObject
{
    public int $maxPerUser;
    public int $maxPerOrder;
    public int $timeLimit;
    public int $userLevelRequired;

    public function __construct(
        int $maxPerUser = 0,
        int $maxPerOrder = 0,
        int $timeLimit = 0,
        int $userLevelRequired = 0
    ) {
        $this->maxPerUser = $maxPerUser;
        $this->maxPerOrder = $maxPerOrder;
        $this->timeLimit = $timeLimit;
        $this->userLevelRequired = $userLevelRequired;
    }

    /**
     * 检查是否启用用户限制
     */
    public function hasUserLimit(): bool
    {
        return $this->maxPerUser > 0;
    }

    /**
     * 检查是否启用订单限制
     */
    public function hasOrderLimit(): bool
    {
        return $this->maxPerOrder > 0;
    }

    /**
     * 检查是否启用时间限制
     */
    public function hasTimeLimit(): bool
    {
        return $this->timeLimit > 0;
    }

    /**
     * 检查是否启用用户等级限制
     */
    public function hasUserLevelLimit(): bool
    {
        return $this->userLevelRequired > 0;
    }

    /**
     * 检查用户兑换数量是否超过限制
     */
    public function checkUserLimit(int $userExchangeCount): bool
    {
        if (!$this->hasUserLimit()) {
            return true;
        }

        return $userExchangeCount < $this->maxPerUser;
    }

    /**
     * 检查订单数量是否超过限制
     */
    public function checkOrderLimit(int $orderQuantity): bool
    {
        if (!$this->hasOrderLimit()) {
            return true;
        }

        return $orderQuantity <= $this->maxPerOrder;
    }

    /**
     * 检查用户等级是否满足要求
     */
    public function checkUserLevel(int $userLevel): bool
    {
        if (!$this->hasUserLevelLimit()) {
            return true;
        }

        return $userLevel >= $this->userLevelRequired;
    }

    /**
     * 检查时间限制
     */
    public function checkTimeLimit(int $currentTime, int $lastExchangeTime): bool
    {
        if (!$this->hasTimeLimit()) {
            return true;
        }

        return ($currentTime - $lastExchangeTime) >= $this->timeLimit;
    }

    /**
     * 获取剩余可兑换数量
     */
    public function getRemainingUserLimit(int $userExchangeCount): int
    {
        if (!$this->hasUserLimit()) {
            return PHP_INT_MAX;
        }

        return max(0, $this->maxPerUser - $userExchangeCount);
    }

    /**
     * 获取最大订单数量
     */
    public function getMaxOrderQuantity(): int
    {
        return $this->hasOrderLimit() ? $this->maxPerOrder : PHP_INT_MAX;
    }

    /**
     * 检查是否有限制
     */
    public function hasAnyLimit(): bool
    {
        return $this->hasUserLimit() || 
               $this->hasOrderLimit() || 
               $this->hasTimeLimit() || 
               $this->hasUserLevelLimit();
    }
} 