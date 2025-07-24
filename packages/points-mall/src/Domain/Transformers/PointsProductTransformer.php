<?php

namespace RedJasmine\PointsMall\Domain\Transformers;

use RedJasmine\PointsMall\Domain\Data\PointsProductData;
use RedJasmine\PointsMall\Domain\Models\PointsProduct;
use RedJasmine\Support\Domain\Transformer\TransformerInterface;

class PointsProductTransformer implements TransformerInterface
{
    public function transform($data, $model) : PointsProduct
    {
        if (!$model instanceof PointsProduct) {
            $model = new PointsProduct();
        }

        if ($data instanceof PointsProductData) {
            $model->owner          = $data->owner;
            $model->title          = $data->title;
            $model->description    = $data->description;
            $model->image          = $data->image;
            $model->point          = $data->point;
            $model->price          = $data->price;
            $model->payment_mode   = $data->paymentMode;
            $model->stock          = $data->stock;
            $model->safety_stock   = $data->safetyStock;
            $model->exchange_limit = $data->exchangeLimit;
            $model->status         = $data->status;
            $model->sort           = $data->sort;
            $model->category_id    = $data->categoryId;
            $model->product_type   = $data->productType;
            $model->product_id     = $data->productId;
        }

        return $model;
    }
} 