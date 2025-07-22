<?php

namespace RedJasmine\PointsMall\Domain\Models\ValueObjects;

use RedJasmine\Support\Domain\Models\ValueObjects\ValueObject;

class StockInfo extends ValueObject
{
    public int $totalStock;
    public int $availableStock;
    public int $lockStock;
    public int $safetyStock;

    public function __construct(
        int $totalStock = 0,
        int $lockStock = 0,
        int $safetyStock = 0
    ) {
        $this->totalStock = $totalStock;
        $this->lockStock = $lockStock;
        $this->safetyStock = $safetyStock;
        $this->availableStock = $this->calculateAvailableStock();
    }

    /**
     * 计算可用库存
     */
    public function getAvailableStock(): int
    {
        return $this->availableStock;
    }

    /**
     * 计算可用库存
     */
    private function calculateAvailableStock(): int
    {
        return max(0, $this->totalStock - $this->lockStock - $this->safetyStock);
    }

    /**
     * 检查是否可以锁定指定数量的库存
     */
    public function canLock(int $quantity): bool
    {
        return $this->availableStock >= $quantity;
    }

    /**
     * 锁定库存
     */
    public function lockStock(int $quantity): bool
    {
        if (!$this->canLock($quantity)) {
            return false;
        }

        $this->lockStock += $quantity;
        $this->availableStock = $this->calculateAvailableStock();
        return true;
    }

    /**
     * 解锁库存
     */
    public function unlockStock(int $quantity): bool
    {
        if ($this->lockStock < $quantity) {
            return false;
        }

        $this->lockStock -= $quantity;
        $this->availableStock = $this->calculateAvailableStock();
        return true;
    }

    /**
     * 减少库存
     */
    public function decreaseStock(int $quantity): bool
    {
        if ($this->availableStock < $quantity) {
            return false;
        }

        $this->totalStock -= $quantity;
        $this->availableStock = $this->calculateAvailableStock();
        return true;
    }

    /**
     * 增加库存
     */
    public function increaseStock(int $quantity): void
    {
        $this->totalStock += $quantity;
        $this->availableStock = $this->calculateAvailableStock();
    }

    /**
     * 设置安全库存
     */
    public function setSafetyStock(int $safetyStock): void
    {
        $this->safetyStock = $safetyStock;
        $this->availableStock = $this->calculateAvailableStock();
    }

    /**
     * 检查是否库存不足
     */
    public function isLowStock(): bool
    {
        return $this->availableStock <= $this->safetyStock;
    }

    /**
     * 检查是否售罄
     */
    public function isSoldOut(): bool
    {
        return $this->availableStock <= 0;
    }
} 