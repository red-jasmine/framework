<?php

namespace RedJasmine\Product\Domain\Stock\Data;

/**
 * 仓库配置操作
 */
class WarehouseStockData extends StockCoreData
{
    /**
     * 总库存（汇总数据，所有仓库 stock 的总和）
     * 对应 product_variants.stock 字段
     * 注意：product_stocks 表中字段名为 stock，汇总后存储在 product_variants.stock
     */
    public int $stock = 0;
    /**
     * 安全库存
     * 对应 product_stocks.safety_stock 字段
     */
    public ?int $safetyStock = null;
    /**
     * 是否启用
     * 对应 product_stocks.is_active 字段
     */
    public bool $isActive = true;
    /**
     * 优先级（用于仓库选择策略）
     */
    public int $priority = 0;

}