<?php

namespace RedJasmine\Product\Domain\Stock\Repositories;

use RedJasmine\Product\Domain\Stock\Models\ProductSku;
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
    public function find($id) : ProductSku;

    /**
     * 初始化库存
     */
    public function init(ProductSku $sku, int $stock);

    /**
     * 重置库存
     */
    public function reset(ProductSku $sku, int $stock): ProductSku;

    /**
     * 增加库存
     */
    public function add(ProductSku $sku, int $stock) : ProductSku;

    /**
     * 减少库存
     */
    public function sub(ProductSku $sku, int $stock) : ProductSku;

    /**
     * 锁定库存
     */
    public function lock(ProductSku $sku, int $stock) : ProductSku;

    /**
     * 解锁库存
     */
    public function unlock(ProductSku $sku, int $stock) : ProductSku;

    /**
     * 确认库存
     */
    public function confirm(ProductSku $sku, int $stock) : ProductSku;

    /**
     * 存储日志
     */
    public function log(ProductStockLog $log) : void;

    /**
     * 根据ID数组查找SKU列表
     * 合并了原ProductSkuReadRepositoryInterface中findList方法
     */
    public function findList(array $ids);
}
