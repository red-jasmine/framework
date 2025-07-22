<?php

namespace RedJasmine\PointsMall\Domain\Transformers;

use RedJasmine\PointsMall\Domain\Data\PointsProductData;
use RedJasmine\PointsMall\Domain\Models\PointsProduct;
use RedJasmine\Support\Domain\Transformer\TransformerInterface;

class PointsProductTransformer implements TransformerInterface
{
    /**
     * 将DTO数据映射到积分商品模型
     */
    public function transform($data, $model): PointsProduct
    {
        if (!$model instanceof PointsProduct) {
            $model = new PointsProduct();
        }

        if ($data instanceof PointsProductData) {
            $model->owner_type = $data->owner->getOwnerType();
            $model->owner_id = $data->owner->getOwnerId();
            $model->title = $data->title;
            $model->description = $data->description;
            $model->image = $data->image;
            $model->point = $data->point;
            $model->price_currency = $data->priceCurrency;
            $model->price_amount = $data->priceAmount;
            $model->payment_mode = $data->paymentMode;
            $model->stock = $data->stock;
            $model->lock_stock = $data->lockStock;
            $model->safety_stock = $data->safetyStock;
            $model->exchange_limit = $data->exchangeLimit;
            $model->status = $data->status;
            $model->sort = $data->sort;
            $model->category_id = $data->categoryId;
            $model->product_type = $data->productType;
            $model->product_id = $data->productId;
        }

        return $model;
    }
} 