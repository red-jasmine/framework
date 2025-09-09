<?php

namespace RedJasmine\Product\Infrastructure\Repositories;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Product\Domain\Series\Models\ProductSeries;
use RedJasmine\Product\Domain\Series\Models\ProductSeriesProduct;
use RedJasmine\Product\Domain\Series\Repositories\ProductSeriesRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Repository;
use Spatie\QueryBuilder\AllowedFilter;

/**
 * 商品系列仓库实现
 *
 * 基于Repository实现，提供商品系列实体的读写操作能力
 */
class ProductSeriesRepository extends Repository implements ProductSeriesRepositoryInterface
{
    /**
     * @var string Eloquent模型类
     */
    protected static string $modelClass = ProductSeries::class;

    /**
     * 根据商品ID查找商品系列
     */
    public function findProductSeries($productId): ProductSeries
    {
        return $this->query()->whereHas('products', function ($query) use ($productId) {
            $query->where('product_id', $productId);
        })->firstOrFail();
    }

    /**
     * 存储商品系列
     */
    public function store(Model $model): Model
    {
        $model->save();
        $model->products->each(function ($product) use ($model) {
            $values = [
                'series_id'  => $model->id,
                'product_id' => $product->product_id,
                'name'       => $product->name,
            ];
            ProductSeriesProduct::updateOrCreate(['product_id' => $product->product_id], $values);
        });
        return $model;
    }

    /**
     * 更新商品系列
     */
    public function update(Model $model): void
    {
        $products = $model->products;
        unset($model->products);
        $model->save();
        $model->products()
              ->where('series_id', $model->id)
              ->whereNotIn('product_id', $products->pluck('product_id')->toArray())->delete();
        $products->each(function ($product) use ($model) {
            $values = [
                'series_id'  => $model->id,
                'product_id' => $product->product_id,
                'name'       => $product->name,
            ];
            ProductSeriesProduct::updateOrCreate(['product_id' => $product->product_id], $values);
        });
    }

    /**
     * 删除商品系列
     */
    public function delete(Model $model)
    {
        $model->products()->delete();
        $model->delete();
    }

    /**
     * 配置允许的过滤器
     */
    protected function allowedFilters($query = null): array
    {
        return [
            AllowedFilter::exact('id'),
            AllowedFilter::partial('name'),
            AllowedFilter::exact('owner_type'),
            AllowedFilter::exact('owner_id'),
            AllowedFilter::exact('status'),
        ];
    }
}
