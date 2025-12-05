<?php

namespace RedJasmine\Product\Domain\Stock\Data;

use RedJasmine\Support\Domain\Contracts\UserInterface;
use RedJasmine\Support\Foundation\Data\Data;

/**
 * SKU 库存数据传输对象
 *
 * 用于库存相关的数据传输，包含变体的库存汇总信息和仓库库存配置
 */
class StockData extends Data
{
    /**
     * 库存拥有者
     * @var UserInterface
     */
    public UserInterface $owner;
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
    public int $warehouseId = 0;


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
    public int $safetyStock = 0;

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
