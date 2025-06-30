<?php

namespace RedJasmine\ShoppingCart\Domain\Contracts;

use RedJasmine\ShoppingCart\Domain\Models\ValueObjects\CartProductIdentity;

interface StockServiceInterface
{
    /**
     * 校验库存是否充足
     *
     * @param CartProductIdentity $identity
     * @param int $quantity
     * @return bool
     */
    public function checkStock(CartProductIdentity $identity, int $quantity): bool;

    /**
     * 获取可用库存数量
     *
     * @param CartProductIdentity $identity
     * @return int
     */
    public function getAvailableStock(CartProductIdentity $identity): int;

    /**
     * 预占库存
     *
     * @param CartProductIdentity $identity
     * @param int $quantity
     * @param string $orderId
     * @return bool
     */
    public function reserveStock(CartProductIdentity $identity, int $quantity, string $orderId): bool;

    /**
     * 释放预占库存
     *
     * @param CartProductIdentity $identity
     * @param int $quantity
     * @param string $orderId
     * @return bool
     */
    public function releaseStock(CartProductIdentity $identity, int $quantity, string $orderId): bool;
} 