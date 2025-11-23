<?php

namespace RedJasmine\Product\Domain\Stock\Repositories;

use Illuminate\Database\Eloquent\Collection;
use RedJasmine\Product\Domain\Stock\Models\ProductStock;
use RedJasmine\Product\Exceptions\StockException;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;

/**
 * 商品库存仓库接口
 *
 * 提供商品库存实体的读写操作统一接口，支持多仓库库存管理
 */
interface ProductStockRepositoryInterface extends RepositoryInterface
{
    // ========================================
    // 查询方法 (Query Methods)
    // ========================================

    /**
     * 根据变体ID和仓库ID查找库存记录
     *
     * @param  int  $variantId  变体ID（SKU ID）
     * @param  int  $warehouseId  仓库ID（0表示总仓/默认仓库）
     *
     * @return ProductStock|null 找到的库存记录，未找到时返回null
     */
    public function findByVariantAndWarehouse(int $variantId, int $warehouseId) : ?ProductStock;

    /**
     * 根据变体ID和仓库ID查找库存记录并加锁
     *
     * @param  int  $variantId  变体ID（SKU ID）
     * @param  int  $warehouseId  仓库ID（0表示总仓/默认仓库）
     *
     * @return ProductStock|null 找到的库存记录，未找到时返回null
     */
    public function findByVariantAndWarehouseLock(int $variantId, int $warehouseId) : ?ProductStock;

    /**
     * 查找变体的所有仓库库存记录
     *
     * @param  int  $variantId  变体ID（SKU ID）
     *
     * @return Collection<ProductStock> 库存记录集合
     */
    public function findByVariant(int $variantId) : Collection;

    /**
     * 根据商品ID和变体ID查找库存记录
     *
     * @param  int  $productId  商品ID
     * @param  int  $variantId  变体ID（SKU ID）
     *
     * @return Collection<ProductStock> 库存记录集合
     */
    public function findByProductAndVariant(int $productId, int $variantId) : Collection;

    /**
     * 批量查询多个变体在指定仓库的库存记录
     *
     * @param  array<int>  $variantIds  变体ID数组
     * @param  int  $warehouseId  仓库ID（0表示总仓/默认仓库）
     *
     * @return Collection<ProductStock> 库存记录集合
     */
    public function findByVariantsAndWarehouse(array $variantIds, int $warehouseId) : Collection;

    /**
     * 获取可用库存数量
     *
     * @param  int  $variantId  变体ID（SKU ID）
     * @param  int  $warehouseId  仓库ID（0表示总仓/默认仓库）
     *
     * @return int 可用库存数量，如果记录不存在返回0
     */
    public function getAvailableStock(int $variantId, int $warehouseId) : int;

    // ========================================
    // 库存操作方法 (Stock Operation Methods)
    // ========================================

    /**
     * 锁定库存（下单时）
     *
     * @param  int  $variantId  变体ID（SKU ID）
     * @param  int  $warehouseId  仓库ID（0表示总仓/默认仓库）
     * @param  int  $quantity  锁定数量
     *
     * @return ProductStock 锁定后的库存记录
     * @throws StockException 库存不足时抛出异常
     */
    public function lockStock(int $variantId, int $warehouseId, int $quantity) : ProductStock;

    /**
     * 解锁库存（订单取消时）
     *
     * @param  int  $variantId  变体ID（SKU ID）
     * @param  int  $warehouseId  仓库ID（0表示总仓/默认仓库）
     * @param  int  $quantity  解锁数量
     *
     * @return ProductStock 解锁后的库存记录
     * @throws StockException 锁定库存不足时抛出异常
     */
    public function unlockStock(int $variantId, int $warehouseId, int $quantity) : ProductStock;

    /**
     * 预留库存（支付成功时）
     * 将锁定库存转为预留库存
     *
     * @param  int  $variantId  变体ID（SKU ID）
     * @param  int  $warehouseId  仓库ID（0表示总仓/默认仓库）
     * @param  int  $quantity  预留数量
     *
     * @return ProductStock 预留后的库存记录
     * @throws StockException 锁定库存不足时抛出异常
     */
    public function reserveStock(int $variantId, int $warehouseId, int $quantity) : ProductStock;

    /**
     * 扣减库存（发货后）
     * 扣减总库存和预留库存
     *
     * @param  int  $variantId  变体ID（SKU ID）
     * @param  int  $warehouseId  仓库ID（0表示总仓/默认仓库）
     * @param  int  $quantity  扣减数量
     *
     * @return ProductStock 扣减后的库存记录
     * @throws StockException 预留库存不足时抛出异常
     */
    public function deductStock(int $variantId, int $warehouseId, int $quantity) : ProductStock;

    /**
     * 增加库存
     *
     * @param  int  $variantId  变体ID（SKU ID）
     * @param  int  $warehouseId  仓库ID（0表示总仓/默认仓库）
     * @param  int  $quantity  增加数量
     *
     * @return ProductStock 增加后的库存记录
     */
    public function addStock(int $variantId, int $warehouseId, int $quantity) : ProductStock;

    /**
     * 重置库存
     * 设置总库存和可用库存为指定值
     *
     * @param  int  $variantId  变体ID（SKU ID）
     * @param  int  $warehouseId  仓库ID（0表示总仓/默认仓库）
     * @param  int  $stock  库存数量
     *
     * @return ProductStock 重置后的库存记录
     */
    public function resetStock(int $variantId, int $warehouseId, int $stock) : ProductStock;

    /**
     * 释放库存（订单取消时，释放预留库存）
     * 将预留库存转回可用库存
     *
     * @param  int  $variantId  变体ID（SKU ID）
     * @param  int  $warehouseId  仓库ID（0表示总仓/默认仓库）
     * @param  int  $quantity  释放数量
     *
     * @return ProductStock 释放后的库存记录
     * @throws StockException 预留库存不足时抛出异常
     */
    public function releaseStock(int $variantId, int $warehouseId, int $quantity) : ProductStock;
}
