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

        ProductSku::where('id', $sku->id)->update([ 'stock' => $stock ]);
        $stockUpdate = DB::raw("stock + $stock");
        Product::where('id', $sku->product_id)->update([ 'stock' => $stockUpdate ]);

    }

    /**
     * 重置库存
     *
     * @param ProductSku $sku
     * @param int        $stock
     *
     * @return int
     * @throws StockException
     */
    public function reset(ProductSku $sku, int $stock) : int
    {
        $sku = ProductSku::withTrashed()
                         ->select([ 'id', 'product_id', 'stock', 'channel_stock', 'lock_stock' ])
                         ->lockForUpdate()
                         ->find($sku->id);
        if (bccomp($sku->stock, $stock, 0) === 0) {
            return 0;
        }
        if (bccomp($stock, $sku->channel_stock, 0) < 0) {
            throw new StockException('活动库存占用');
        }
        $quantity    = (int)bcsub($stock, $sku->stock, 0);
        $stockUpdate = DB::raw("stock + $quantity");
        ProductSku::withTrashed()->where('id', $sku->id)->update([ 'stock' => $stockUpdate ]);
        Product::withTrashed()->where('id', $sku->product_id)->update([ 'stock' => $stockUpdate ]);

        return (int)$quantity;
    }

    public function add(ProductSku $sku, int $stock) : void
    {
        $attributes = [
            'stock' => DB::raw("stock + $stock"),
        ];
        ProductSku::where('id', $sku->id)->update($attributes);
        Product::where('id', $sku->product_id)->update($attributes);
    }


    /**
     * 减库存
     *
     * @param ProductSku $sku
     * @param int        $stock
     *
     * @return void
     * @throws \RedJasmine\Product\Exceptions\StockException
     */
    public function sub(ProductSku $sku, int $stock) : void
    {
        $attributes = [
            'stock' => DB::raw("stock - $stock"),
        ];
        $rows       = ProductSku::where('id', $sku->id)->where('stock', '>=', $stock)->update($attributes);
        if ($rows <= 0) {
            throw new StockException('库存不足');
        }
        Product::where('id', $sku->product_id)->update($attributes);
    }

    /**
     * @param ProductSku $sku
     * @param int        $stock
     *
     * @return void
     * @throws StockException
     */
    public function lock(ProductSku $sku, int $stock) : void
    {
        $attributes = [
            'stock' => DB::raw("stock - $stock"),
            'lock_stock'  => DB::raw("lock_stock + $stock"),
        ];
        $rows       = ProductSku::where('id', $sku->id)->where('stock', '>=', $stock)->update($attributes);
        if ($rows <= 0) {
            throw new StockException('库存不足');
        }
        Product::where('id', $sku->product_id)->update($attributes);

    }

    /**
     * 解锁库存
     *
     * @param ProductSku $sku
     * @param int        $stock
     *
     * @return void
     * @throws \RedJasmine\Product\Exceptions\StockException
     */
    public function unlock(ProductSku $sku, int $stock) : void
    {
        $attributes = [
            'stock' => DB::raw("stock + $stock"),
            'lock_stock'  => DB::raw("lock_stock - $stock"),
        ];
        $rows       = ProductSku::where('id', $sku->id)->where('lock_stock', '>=', $stock)->update($attributes);
        if ($rows <= 0) {
            throw new StockException('锁定库存不足');
        }
        Product::where('id', $sku->product_id)->update($attributes);

    }

    /**
     * 锁定
     *
     * @param ProductSku $sku
     * @param int        $stock
     *
     * @return void
     * @throws \RedJasmine\Product\Exceptions\StockException
     */
    public function confirm(ProductSku $sku, int $stock) : void
    {
        $attributes = [
            'lock' => DB::raw("lock_stock - $stock"),
        ];
        $rows       = ProductSku::where('id', $sku->id)->where('lock_stock', '>=', $stock)->update($attributes);
        if ($rows <= 0) {
            throw new StockException('锁定库存不足');
        }
        Product::where('id', $sku->product_id)->update($attributes);
    }


}
