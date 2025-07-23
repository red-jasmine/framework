<?php

namespace RedJasmine\PointsMall\Domain\Transformers;

use RedJasmine\PointsMall\Domain\Data\PointsProductData;
use RedJasmine\PointsMall\Domain\Models\PointsProduct;
use RedJasmine\Support\Domain\Transformer\TransformerInterface;

class PointsProductTransformer implements TransformerInterface
{
    public function transform($data, $model): PointsProduct
    {
        if (!$model instanceof PointsProduct) {
            $model = new PointsProduct();
        }

        if ($data instanceof PointsProductData) {
            $model->owner_type = get_class($data->owner);
            $model->owner_id = $data->owner->getId();
            $model->title = $data->title;
            $model->description = $data->description;
            $model->image = $data->image;
            $model->point = $data->point;
            $model->price_currency = $data->price_currency;
            $model->price_amount = $data->price_amount;
            $model->payment_mode = $data->payment_mode;
            $model->stock = $data->stock;
            $model->lock_stock = $data->lock_stock;
            $model->safety_stock = $data->safety_stock;
            $model->exchange_limit = $data->exchange_limit;
            $model->status = $data->status;
            $model->sort = $data->sort;
            $model->category_id = $data->category_id;
            $model->product_type = $data->product_type;
            $model->product_id = $data->product_id;
        }

        return $model;
    }
} 