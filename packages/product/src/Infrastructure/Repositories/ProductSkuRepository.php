<?php

namespace RedJasmine\Product\Infrastructure\Repositories;

use Illuminate\Support\Facades\DB;
use RedJasmine\Product\Domain\Stock\Models\Product;
use RedJasmine\Product\Domain\Stock\Models\ProductVariant;
use RedJasmine\Product\Domain\Stock\Models\ProductStockLog;
use RedJasmine\Product\Domain\Stock\Repositories\ProductSkuRepositoryInterface;
use RedJasmine\Product\Exceptions\StockException;
use RedJasmine\Support\Infrastructure\Repositories\Repository;
use Spatie\QueryBuilder\AllowedFilter;

/**
 * 商品SKU仓库实现
 *
 * 基于Repository实现，提供商品SKU实体的读写操作能力
 */
class ProductSkuRepository extends Repository implements ProductSkuRepositoryInterface
{
    /**
     * @var string Eloquent模型类
     */
    protected static string $modelClass = ProductVariant::class;

    /**
     * 查找 SKU
     */
    public function find($id): ProductVariant
    {
        return static::$modelClass::withTrashed()->findOrFail($id);
    }

    /**
     * 根据ID数组查找SKU列表
     */
    public function findList(array $ids)
    {
        return $this->query()->whereIn('id', $ids)->get();
    }

    /**
     * 存储日志
     */
    public function log(ProductStockLog $log): void
    {
        $log->save();
    }

    /**
     * 初始化库存
     */
    public function init(ProductVariant $sku, int $stock): void
    {
        ProductVariant::where('id', $sku->id)->update(['stock' => $stock]);
        $stockUpdate = DB::raw("stock + $stock");
        Product::where('id', $sku->product_id)->update(['stock' => $stockUpdate]);
    }

    /**
     * 重置库存
     */
    public function reset(ProductVariant $sku, int $stock): ProductVariant
    {
        $sku = ProductVariant::withTrashed()
                             ->lockForUpdate()
                             ->find($sku->id);
        if (bccomp($sku->stock, $stock, 0) === 0) {
            return $sku;
        }
        if (bccomp($stock, $sku->channel_stock, 0) < 0) {
            throw new StockException('活动库存占用');
        }
        $sku->setOldStock($sku->stock);
        $sku->setOldLockStock($sku->lock_stock);

        $quantity   = (int) bcsub($stock, $sku->stock, 0);
        $sku->stock = $sku->stock + $quantity;
        $sku->save();

        $stockUpdate = DB::raw("stock + $quantity");
        Product::withTrashed()->where('id', $sku->product_id)->update(['stock' => $stockUpdate]);

        return $sku;
    }

    /**
     * 增加库存
     */
    public function add(ProductVariant $sku, int $stock): ProductVariant
    {
        $sku = ProductVariant::lockForUpdate()->find($sku->id);
        $sku->setOldLockStock($sku->lock_stock);
        $sku->setOldStock($sku->stock);
        $sku->stock = $sku->stock + $stock;
        $sku->save();

        $attributes = [
            'stock' => DB::raw("stock + $stock"),
        ];
        Product::where('id', $sku->product_id)->update($attributes);
        return $sku;
    }

    /**
     * 减少库存
     */
    public function sub(ProductVariant $sku, int $stock): ProductVariant
    {
        $sku = ProductVariant::lockForUpdate()->find($sku->id);
        if (bccomp($sku->stock, $stock, 0) < 0) {
            throw new StockException('库存不足');
        }
        $sku->setOldLockStock($sku->lock_stock);
        $sku->setOldStock($sku->stock);
        $sku->stock = $sku->stock - $stock;
        $sku->save();

        $attributes = [
            'stock' => DB::raw("stock - $stock"),
        ];
        Product::where('id', $sku->product_id)->update($attributes);

        return $sku;
    }

    /**
     * 锁定库存
     */
    public function lock(ProductVariant $sku, int $stock): ProductVariant
    {
        $sku = ProductVariant::lockForUpdate()->find($sku->id);
        if (bccomp($sku->stock, $stock, 0) < 0) {
            throw new StockException('库存不足');
        }
        $sku->setOldLockStock($sku->lock_stock);
        $sku->setOldStock($sku->stock);

        $sku->stock      = $sku->stock - $stock;
        $sku->lock_stock = $sku->lock_stock + $stock;
        $sku->save();

        $attributes = [
            'stock'      => DB::raw("stock - $stock"),
            'lock_stock' => DB::raw("lock_stock + $stock"),
        ];
        Product::where('id', $sku->product_id)->update($attributes);
        return $sku;
    }

    /**
     * 解锁库存
     */
    public function unlock(ProductVariant $sku, int $stock): ProductVariant
    {
        $sku = ProductVariant::lockForUpdate()->find($sku->id);
        if (bccomp($sku->lock_stock, $stock, 0) <= 0) {
            throw new StockException('锁定库存不足');
        }
        $sku->setOldLockStock($sku->lock_stock);
        $sku->setOldStock($sku->stock);
        $sku->stock      = $sku->stock + $stock;
        $sku->lock_stock = $sku->lock_stock - $stock;
        $sku->save();
        $attributes = [
            'stock'      => DB::raw("stock + $stock"),
            'lock_stock' => DB::raw("lock_stock - $stock"),
        ];

        Product::where('id', $sku->product_id)->update($attributes);

        return $sku;
    }

    /**
     * 确认库存
     */
    public function confirm(ProductVariant $sku, int $stock): ProductVariant
    {
        $sku = ProductVariant::lockForUpdate()->find($sku->id);

        if (bccomp($sku->lock_stock, $stock, 0) <= 0) {
            throw new StockException('锁定库存不足');
        }
        $sku->setOldLockStock($sku->lock_stock);
        $sku->setOldStock($sku->stock);
        $sku->lock_stock = $sku->lock_stock - $stock;

        $sku->save();

        $attributes = [
            'lock_stock' => DB::raw("lock_stock - $stock"),
        ];
        Product::where('id', $sku->product_id)->update($attributes);
        return $sku;
    }

    /**
     * 配置允许的过滤器
     */
    protected function allowedFilters($query = null): array
    {
        return [
            AllowedFilter::exact('id'),
            AllowedFilter::exact('product_id'),
            AllowedFilter::exact('owner_type'),
            AllowedFilter::exact('owner_id'),
            AllowedFilter::exact('status'),
        ];
    }
}
