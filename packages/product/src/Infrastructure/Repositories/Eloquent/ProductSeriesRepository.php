<?php

namespace RedJasmine\Product\Infrastructure\Repositories\Eloquent;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Product\Domain\Series\Models\ProductSeries;
use RedJasmine\Product\Domain\Series\Models\ProductSeriesProduct;
use RedJasmine\Product\Domain\Series\Repositories\ProductSeriesRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Repository;

class ProductSeriesRepository extends Repository implements ProductSeriesRepositoryInterface
{

    protected static string $modelClass = ProductSeries::class;

    /**
     * @param  ProductSeries  $model
     *
     * @return Model
     */
    public function store(Model $model) : Model
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
     * @param  ProductSeries  $model
     *
     * @return void
     */
    public function update(Model $model) : void
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

    public function delete(Model $model)
    {
        $model->products()->delete();
        $model->delete();
    }


}
