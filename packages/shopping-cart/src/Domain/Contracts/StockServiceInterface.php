<?php

namespace RedJasmine\ShoppingCart\Domain\Contracts;

use RedJasmine\ShoppingCart\Domain\Data\CartStockInfo;
use RedJasmine\ShoppingCart\Domain\Models\ValueObjects\CartProduct;

interface StockServiceInterface
{


    /**
     * 获取可用库存数量
     *
     * @param  CartProduct  $product
     * @param  int  $quantity
     *
     * @return CartStockInfo
     */
    public function getAvailableStock(CartProduct $product, int $quantity) : CartStockInfo;

    /**
     * 预占库存
     *
     * @param  CartProduct  $product
     * @param  int  $quantity
     * @param  string  $orderId
     *
     * @return bool
     */
    public function reserveStock(CartProduct $product, int $quantity, string $orderId) : bool;

    /**
     * 释放预占库存
     *
     * @param  CartProduct  $product
     * @param  int  $quantity
     * @param  string  $orderId
     *
     * @return bool
     */
    public function releaseStock(CartProduct $product, int $quantity, string $orderId) : bool;
} 