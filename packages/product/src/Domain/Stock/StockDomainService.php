<?php

namespace RedJasmine\Product\Domain\Stock;

use Illuminate\Support\Facades\DB;
use RedJasmine\Product\Domain\Stock\Models\Product;
use RedJasmine\Product\Domain\Stock\Models\ProductVariant;
use RedJasmine\Product\Exceptions\StockException;
use RedJasmine\Support\Foundation\Service\Service;

class StockDomainService extends Service
{


    public function init(int $skuId, int $productId, int $stock) : void
    {
        // TODO 改造
        ProductVariant::where('id', $skuId)->update(['stock' => $stock]);
        $stockUpdate = DB::raw("stock + $stock");
        Product::where('id', $productId)->update(['stock' => $stockUpdate]);
    }


    /**
     * 重置库存
     *
     * @param  int  $skuId
     * @param  int  $productId
     * @param  int  $stock
     *
     * @return int
     * @throws StockException|\Throwable
     */
    public function reset(int $skuId, int $productId, int $stock) : int
    {
        $sku = ProductVariant::withTrashed()
                             ->select(['id', 'product_id', 'stock', 'channel_stock', 'lock_stock'])
                             ->lockForUpdate()
                             ->find($skuId);
        if (bccomp($sku->stock, $stock, 0) === 0) {

            return 0;
        }
        if (bccomp($stock, $sku->channel_stock, 0) < 0) {
            throw new StockException('活动库存占用');
        }
        $quantity    = bcsub($stock, $sku->stock, 0);
        $stockUpdate = DB::raw("stock + $quantity");
        ProductVariant::withTrashed()->where('id', $sku->id)->update(['stock' => $stockUpdate]);
        Product::withTrashed()->where('id', $productId)->update(['stock' => $stockUpdate]);

        return $quantity;


    }

}
