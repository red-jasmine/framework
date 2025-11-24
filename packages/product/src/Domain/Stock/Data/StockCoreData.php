<?php

namespace RedJasmine\Product\Domain\Stock\Data;

use RedJasmine\Support\Data\Data;

class StockCoreData extends Data
{
    const DEFAULT_WAREHOUSE_ID = 0;
    /**
     * 变体ID（SKU ID）
     */
    public int $variantId;
    /**
     * 商品ID
     */
    public int $productId;
    /**
     * 仓库ID
     * 0 表示总仓/默认仓库/简单模式
     * 具体ID（> 0）表示高级库存模式下的仓库
     */
    public int $warehouseId = self::DEFAULT_WAREHOUSE_ID;


    public function defaultWarehouse() : static
    {
        $this->warehouseId = self::DEFAULT_WAREHOUSE_ID;

        return $this;
    }
}