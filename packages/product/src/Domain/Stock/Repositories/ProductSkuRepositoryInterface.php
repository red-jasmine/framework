<?php

namespace RedJasmine\Product\Domain\Stock\Repositories;

use RedJasmine\Product\Domain\Stock\Models\ProductSku;
use RedJasmine\Product\Domain\Stock\Models\ProductStockLog;


interface ProductSkuRepositoryInterface
{
    public function find($id) : ProductSku;


    public function init(ProductSku $sku, int $stock);


    public function reset(ProductSku $sku, int $stock);


    public function add(ProductSku $sku, int $stock);


    public function sub(ProductSku $sku, int $stock): ProductSku;


    public function lock(ProductSku $sku, int $stock) : ProductSku;

    public function unlock(ProductSku $sku, int $stock) : ProductSku;

    public function confirm(ProductSku $sku, int $stock) : ProductSku;


    /**
     * 存储日志
     *
     * @param  ProductStockLog  $log
     *
     * @return void
     */
    public function log(ProductStockLog $log) : void;


}
