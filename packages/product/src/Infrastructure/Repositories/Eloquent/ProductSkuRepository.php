<?php

namespace RedJasmine\Product\Infrastructure\Repositories\Eloquent;

use Illuminate\Support\Facades\DB;
use RedJasmine\Product\Domain\Stock\Models\Product;
use RedJasmine\Product\Domain\Stock\Models\ProductSku;
use RedJasmine\Product\Domain\Stock\Models\ProductStockLog;
use RedJasmine\Product\Domain\Stock\Repositories\ProductSkuRepositoryInterface;
use RedJasmine\Product\Exceptions\StockException;

class ProductSkuRepository implements ProductSkuRepositoryInterface
{
    protected static string $eloquentModelClass = ProductSku::class;

    public function find($id) : ProductSku
    {
        return static::$eloquentModelClass::withTrashed()->findOrFail($id);
    }


    public function log(ProductStockLog $log) : void
    {
        $log->save();
    }

    public function init(ProductSku $sku, int $stock) : void
    {

        ProductSku::where('id', $sku->id)->update(['stock' => $stock]);
        $stockUpdate = DB::raw("stock + $stock");
        Product::where('id', $sku->product_id)->update(['stock' => $stockUpdate]);

    }

    /**
     * 重置库存
     *
     * @param  ProductSku  $sku
     * @param  int  $stock
     *
     * @return ProductSku
     * @throws StockException
     */
    public function reset(ProductSku $sku, int $stock) : ProductSku
    {

        $sku = ProductSku::withTrashed()
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

    public function add(ProductSku $sku, int $stock) : ProductSku
    {
        $sku = ProductSku::lockForUpdate()->find($sku->id);
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
     * 减库存
     *
     * @param  ProductSku  $sku
     * @param  int  $stock
     *
     * @return ProductSku
     * @throws StockException
     */
    public function sub(ProductSku $sku, int $stock) : ProductSku
    {
        $sku = ProductSku::lockForUpdate()->find($sku->id);
        if (bccomp($sku->stock, $stock, 0) < 0) {
            throw new StockException('库存不足');
        }
        $sku->setOldLockStock($sku->lock_stock);
        $sku->setOldStock($sku->stock);
        $sku->stock = $sku->stock - $stock;
        $sku->save();

        // 同步中库存
        $attributes = [
            'stock' => DB::raw("stock - $stock"),
        ];
        Product::where('id', $sku->product_id)->update($attributes);

        return $sku;
    }

    /**
     * @param  ProductSku  $sku
     * @param  int  $stock
     *
     * @return ProductSku
     * @throws StockException
     */
    public function lock(ProductSku $sku, int $stock) : ProductSku
    {
        $sku = ProductSku::lockForUpdate()->find($sku->id);
        if (bccomp($sku->stock, $stock, 0) < 0) {
            throw new StockException('库存不足');
        }
        $sku->setOldLockStock($sku->lock_stock);
        $sku->setOldStock($sku->stock);

        $sku->stock      = $sku->stock - $stock;
        $sku->lock_stock = $sku->lock_stock + $stock;
        $sku->save();
        // 同步商品库存
        $attributes = [
            'stock'      => DB::raw("stock - $stock"),
            'lock_stock' => DB::raw("lock_stock + $stock"),
        ];
        Product::where('id', $sku->product_id)->update($attributes);
        return $sku;
    }

    /**
     * 解锁库存
     *
     * @param  ProductSku  $sku
     * @param  int  $stock
     *
     * @return ProductSku
     * @throws StockException
     */
    public function unlock(ProductSku $sku, int $stock) : ProductSku
    {
        $sku = ProductSku::lockForUpdate()->find($sku->id);
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
     * 锁定
     *
     * @param  ProductSku  $sku
     * @param  int  $stock
     *
     * @return ProductSku
     * @throws StockException
     */
    public function confirm(ProductSku $sku, int $stock) : ProductSku
    {
        $sku = ProductSku::lockForUpdate()->find($sku->id);

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


}
