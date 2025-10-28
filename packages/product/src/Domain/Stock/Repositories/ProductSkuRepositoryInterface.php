<?php

namespace RedJasmine\Product\Domain\Stock\Repositories;

use RedJasmine\Product\Domain\Stock\Models\ProductVariant;
use RedJasmine\Product\Domain\Stock\Models\ProductStockLog;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;

/**
 * 商品SKU仓库接口
 *
 * 提供商品SKU实体的读写操作统一接口
 */
interface ProductSkuRepositoryInterface extends RepositoryInterface
{
    /**
     * 查找 SKU
     */
    public function find($id) : ProductVariant;

    /**
     * 初始化库存
     */
    public function init(ProductVariant $sku, int $stock);

    /**
     * 重置库存
     */
    public function reset(ProductVariant $sku, int $stock): ProductVariant;

    /**
     * 增加库存
     */
    public function add(ProductVariant $sku, int $stock) : ProductVariant;

    /**
     * 减少库存
     */
    public function sub(ProductVariant $sku, int $stock) : ProductVariant;

    /**
     * 锁定库存
     */
    public function lock(ProductVariant $sku, int $stock) : ProductVariant;

    /**
     * 解锁库存
     */
    public function unlock(ProductVariant $sku, int $stock) : ProductVariant;

    /**
     * 确认库存
     */
    public function confirm(ProductVariant $sku, int $stock) : ProductVariant;

    /**
     * 存储日志
     */
    public function log(ProductStockLog $log) : void;

    /**
     * 根据ID数组查找SKU列表
     */
    public function findList(array $ids);
}
