<?php

namespace RedJasmine\Product\Infrastructure\Repositories;

use Illuminate\Database\Eloquent\Collection;
use RedJasmine\Product\Domain\Stock\Models\ProductStock;
use RedJasmine\Product\Domain\Stock\Models\ProductVariant;
use RedJasmine\Product\Domain\Stock\Repositories\ProductStockRepositoryInterface;
use RedJasmine\Product\Exceptions\StockException;
use RedJasmine\Support\Infrastructure\Repositories\Repository;
use Spatie\QueryBuilder\AllowedFilter;

/**
 * 商品库存仓库实现
 *
 * 基于Repository实现，提供商品库存实体的读写操作能力，支持多仓库库存管理
 */
class ProductStockRepository extends Repository implements ProductStockRepositoryInterface
{
    /**
     * @var string Eloquent模型类
     */
    protected static string $modelClass = ProductStock::class;

    // ========================================
    // 查询方法 (Query Methods)
    // ========================================

    /**
     * 根据变体ID和仓库ID查找库存记录
     */
    public function findByVariantAndWarehouse(int $variantId, int $warehouseId) : ?ProductStock
    {
        return static::$modelClass::where('variant_id', $variantId)
            ->where('warehouse_id', $warehouseId)
            ->first();
    }

    /**
     * 根据变体ID和仓库ID查找库存记录并加锁
     */
    public function findByVariantAndWarehouseLock(int $variantId, int $warehouseId) : ?ProductStock
    {
        return static::$modelClass::where('variant_id', $variantId)
            ->where('warehouse_id', $warehouseId)
            ->lockForUpdate()
            ->first();
    }

    /**
     * 查找变体的所有仓库库存记录
     */
    public function findByVariant(int $variantId) : Collection
    {
        return static::$modelClass::where('variant_id', $variantId)->get();
    }

    /**
     * 根据商品ID和变体ID查找库存记录
     */
    public function findByProductAndVariant(int $productId, int $variantId) : Collection
    {
        return static::$modelClass::where('product_id', $productId)
            ->where('variant_id', $variantId)
            ->get();
    }

    /**
     * 批量查询多个变体在指定仓库的库存记录
     */
    public function findByVariantsAndWarehouse(array $variantIds, int $warehouseId) : Collection
    {
        return static::$modelClass::whereIn('variant_id', $variantIds)
            ->where('warehouse_id', $warehouseId)
            ->get();
    }

    /**
     * 获取可用库存数量
     */
    public function getAvailableStock(int $variantId, int $warehouseId) : int
    {
        $stock = $this->findByVariantAndWarehouse($variantId, $warehouseId);

        if (!$stock) {
            return 0;
        }

        // available_stock = stock - locked_stock - reserved_stock
        return max(0, $stock->available_stock);
    }

    /**
     * 计算并更新可用库存
     * available_stock = stock - locked_stock - reserved_stock
     */
    protected function updateAvailableStock(ProductStock $stock) : void
    {
        $stock->available_stock = max(0, $stock->stock - $stock->locked_stock - $stock->reserved_stock);
    }

    // ========================================
    // 库存操作方法 (Stock Operation Methods)
    // ========================================

    /**
     * 锁定库存（下单时）
     * @throws StockException
     */
    public function lock(int $variantId, int $warehouseId, int $quantity) : ProductStock
    {
        $stock = $this->findByVariantAndWarehouseLock($variantId, $warehouseId);

        if (!$stock) {
            throw new StockException("库存记录不存在：变体ID={$variantId}, 仓库ID={$warehouseId}");
        }

        // 计算可用库存
        $availableStock = $stock->available_stock;

        if ($availableStock < $quantity) {
            throw new StockException("库存不足，可用库存：{$availableStock}，需要：{$quantity}");
        }

        // 锁定库存：available_stock -= quantity, locked_stock += quantity
        $stock->locked_stock += $quantity;
        $this->updateAvailableStock($stock);
        $stock->save();

        return $stock;
    }

    /**
     * 解锁库存（订单取消时）
     */
    public function unlock(int $variantId, int $warehouseId, int $quantity) : ProductStock
    {
        $stock = $this->findByVariantAndWarehouseLock($variantId, $warehouseId);

        if (!$stock) {
            throw new StockException("库存记录不存在：变体ID={$variantId}, 仓库ID={$warehouseId}");
        }

        if ($stock->locked_stock < $quantity) {
            throw new StockException("锁定库存不足，锁定库存：{$stock->locked_stock}，需要：{$quantity}");
        }

        // 解锁库存：locked_stock -= quantity, available_stock += quantity
        $stock->locked_stock -= $quantity;
        $this->updateAvailableStock($stock);
        $stock->save();

        return $stock;
    }

    /**
     * 预留库存（支付成功时）
     * 将锁定库存转为预留库存
     */
    public function reserve(int $variantId, int $warehouseId, int $quantity) : ProductStock
    {
        $stock = $this->findByVariantAndWarehouseLock($variantId, $warehouseId);

        if (!$stock) {
            throw new StockException("库存记录不存在：变体ID={$variantId}, 仓库ID={$warehouseId}");
        }

        if ($stock->locked_stock < $quantity) {
            throw new StockException("锁定库存不足，锁定库存：{$stock->locked_stock}，需要：{$quantity}");
        }

        // 预留库存：locked_stock -= quantity, reserved_stock += quantity
        $stock->locked_stock -= $quantity;
        $stock->reserved_stock += $quantity;
        $this->updateAvailableStock($stock);
        $stock->save();

        return $stock->fresh();
    }

    /**
     * 扣减库存（发货后）
     * 扣减总库存和预留库存
     */
    public function deduct(int $variantId, int $warehouseId, int $quantity) : ProductStock
    {
        $stock = $this->findByVariantAndWarehouseLock($variantId, $warehouseId);

        if (!$stock) {
            throw new StockException("库存记录不存在：变体ID={$variantId}, 仓库ID={$warehouseId}");
        }

        if ($stock->reserved_stock < $quantity) {
            throw new StockException("预留库存不足，预留库存：{$stock->reserved_stock}，需要：{$quantity}");
        }

        // 扣减库存：reserved_stock -= quantity, stock -= quantity
        $stock->reserved_stock -= $quantity;
        $stock->stock -= $quantity;
        $this->updateAvailableStock($stock);
        $stock->save();

        return $stock->fresh();
    }

    /**
     * 增加库存
     */
    public function add(int $variantId, int $warehouseId, int $quantity) : ProductStock
    {
        $stock = $this->findByVariantAndWarehouseLock($variantId, $warehouseId);

        if (!$stock) {
            throw new StockException("库存记录不存在：变体ID={$variantId}, 仓库ID={$warehouseId}");
        }

        // 增加库存：stock += quantity
        $stock->stock += $quantity;
        $this->updateAvailableStock($stock);
        $stock->save();

        return $stock;
    }

    /**
     * 减少库存
     * @throws StockException
     */
    public function sub(int $variantId, int $warehouseId, int $quantity) : ProductStock
    {
        $stock = $this->findByVariantAndWarehouseLock($variantId, $warehouseId);

        if (!$stock) {
            throw new StockException("库存记录不存在：变体ID={$variantId}, 仓库ID={$warehouseId}");
        }

        // 增加库存：stock += quantity
        $stock->stock += $quantity;
        $this->updateAvailableStock($stock);
        $stock->save();

        return $stock;
    }

    /**
     * 重置库存
     * 设置总库存和可用库存为指定值
     * 如果库存记录不存在，会自动创建一条新记录
     *
     * @param  int  $variantId  变体ID（SKU ID）
     * @param  int  $warehouseId  仓库ID（0表示总仓/默认仓库）
     * @param  int  $stockValue  库存数量
     * @param  int|null  $productId  商品ID（创建库存记录时需要，可选）
     * @param  string|null  $ownerType  所有者类型（创建库存记录时需要，可选）
     * @param  string|null  $ownerId  所有者ID（创建库存记录时需要，可选）
     *
     * @return ProductStock 重置后的库存记录
     * @throws StockException
     */
    public function reset(
        int $variantId,
        int $warehouseId,
        int $stockValue,
        ?int $productId = null,
        ?string $ownerType = null,
        ?string $ownerId = null
    ) : ProductStock {
        $stock = $this->findByVariantAndWarehouseLock($variantId, $warehouseId);

        if (!$stock) {
            // 如果库存记录不存在，创建一条新记录
            $stock = $this->createStockRecord(
                $variantId,
                $warehouseId,
                $stockValue,
                $productId,
                $ownerType,
                $ownerId
            );
        } else {
            // 重置库存：设置 stock 为指定值
            // 注意：locked_stock 和 reserved_stock 保持不变
            $stock->stock = $stockValue;
            $this->updateAvailableStock($stock);
            $stock->save();
        }

        return $stock;
    }

    /**
     * 创建库存记录
     * 用于首次为某个变体在指定仓库创建库存记录
     *
     * @param  int  $variantId  变体ID（SKU ID）
     * @param  int  $warehouseId  仓库ID（0表示总仓/默认仓库）
     * @param  int  $stockValue  初始库存值
     * @param  int|null  $productId  商品ID（可选，未提供时从变体查询）
     * @param  string|null  $ownerType  所有者类型（可选，未提供时从变体查询）
     * @param  string|null  $ownerId  所有者ID（可选，未提供时从变体查询）
     *
     * @return ProductStock 创建的库存记录
     * @throws StockException 变体不存在或必要信息缺失时抛出异常
     */
    protected function createStockRecord(
        int $variantId,
        int $warehouseId,
        int $stockValue,
        ?int $productId = null,
        ?string $ownerType = null,
        ?string $ownerId = null
    ) : ProductStock {
        // 如果必要信息未提供，则从变体查询（向后兼容，但不推荐）
        if ($productId === null || $ownerType === null || $ownerId === null) {
            $variant = ProductVariant::withTrashed()
                ->lockForUpdate()
                ->find($variantId);

            if (!$variant) {
                throw new StockException("变体不存在：变体ID={$variantId}");
            }

            $productId = $productId ?? $variant->product_id;
            $ownerType = $ownerType ?? $variant->owner_type;
            $ownerId = $ownerId ?? $variant->owner_id;
        }

        // 创建库存记录
        $stock = new ProductStock();
        $stock->product_id = $productId;
        $stock->variant_id = $variantId;
        $stock->warehouse_id = $warehouseId;
        $stock->owner_type = $ownerType;
        $stock->owner_id = $ownerId;
        $stock->stock = $stockValue;
        $stock->available_stock = $stockValue; // 初始时 locked_stock 和 reserved_stock 为 0
        $stock->locked_stock = 0;
        $stock->reserved_stock = 0;
        $stock->safety_stock = 0;
        $stock->is_active = true;
        $stock->save();

        return $stock;
    }

    /**
     * 释放库存（订单取消时，释放预留库存）
     * 将预留库存转回可用库存
     */
    public function release(int $variantId, int $warehouseId, int $quantity) : ProductStock
    {
        $stock = $this->findByVariantAndWarehouseLock($variantId, $warehouseId);

        if (!$stock) {
            throw new StockException("库存记录不存在：变体ID={$variantId}, 仓库ID={$warehouseId}");
        }

        if ($stock->reserved_stock < $quantity) {
            throw new StockException("预留库存不足，预留库存：{$stock->reserved_stock}，需要：{$quantity}");
        }

        // 释放库存：reserved_stock -= quantity
        $stock->reserved_stock -= $quantity;
        $this->updateAvailableStock($stock);
        $stock->save();

        return $stock->fresh();
    }

    // ========================================
    // 查询构建器配置 (Query Builder Configuration)
    // ========================================

    /**
     * 配置允许的过滤器
     */
    protected function allowedFilters($query = null): array
    {
        return [
            AllowedFilter::exact('id'),
            AllowedFilter::exact('product_id'),
            AllowedFilter::exact('variant_id'),
            AllowedFilter::exact('warehouse_id'),
            AllowedFilter::exact('owner_type'),
            AllowedFilter::exact('owner_id'),
            AllowedFilter::exact('is_active'),
        ];
    }
}

