<?php

namespace RedJasmine\Shopping\Domain\Contracts;

use RedJasmine\Ecommerce\Domain\Models\ValueObjects\ProductIdentity;
use RedJasmine\Shopping\Domain\Data\CartStockInfo;

interface StockServiceInterface
{


    /**
     * 获取可用库存数量
     *
     * @param  ProductIdentity  $product
     * @param  int  $quantity
     *
     * @return CartStockInfo
     */
    public function getAvailableStock(ProductIdentity $product, int $quantity) : CartStockInfo;

    /**
     * 预占库存
     *
     * @param  ProductIdentity  $product
     * @param  int  $quantity
     * @param  string  $orderId
     *
     * @return bool
     */
    public function reserveStock(ProductIdentity $product, int $quantity, string $orderId) : bool;

    /**
     * 释放预占库存
     *
     * @param  ProductIdentity  $product
     * @param  int  $quantity
     * @param  string  $orderId
     *
     * @return bool
     */
    public function releaseStock(ProductIdentity $product, int $quantity, string $orderId) : bool;
} 