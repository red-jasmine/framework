<?php

namespace RedJasmine\Shopping\Domain\Contracts;

use RedJasmine\Ecommerce\Domain\Data\ProductIdentity;
use RedJasmine\Ecommerce\Domain\Data\StockInfo;

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
     * 扣减库存
     *
     * @param  ProductIdentity  $product
     * @param  int  $quantity
     * @param  string  $orderProductNo
     *
     * @return bool
     */
    public function subStock(ProductIdentity $product, int $quantity, string $orderProductNo) : bool;


    public function lockStock(ProductIdentity $product, int $quantity, string $orderProductNo) : bool;

} 