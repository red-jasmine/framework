<?php

namespace RedJasmine\Shopping\Domain\Contracts;

use RedJasmine\Ecommerce\Domain\Data\ProductIdentity;
use RedJasmine\Shopping\Domain\Data\StockInfo;

interface StockServiceInterface
{


    /**
     * 获取可用库存数量
     *
     * @param  ProductIdentity  $product
     * @param  int  $quantity
     *
     * @return StockInfo
     */
    public function getStockInfo(ProductIdentity $product, int $quantity) : StockInfo;

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