<?php

namespace RedJasmine\PointsMall\Domain\Transformers;

use RedJasmine\PointsMall\Domain\Data\PointsExchangeOrderData;
use RedJasmine\PointsMall\Domain\Models\PointsExchangeOrder;
use RedJasmine\Support\Domain\Transformer\TransformerInterface;

class PointsExchangeOrderTransformer implements TransformerInterface
{
    /**
     * 将DTO数据映射到积分兑换订单模型
     */
    public function transform($data, $model) : PointsExchangeOrder
    {
        if (!$model instanceof PointsExchangeOrder) {
            $model = new PointsExchangeOrder();
        }

        if ($data instanceof PointsExchangeOrderData) {
            $model->owner            = $data->pointsProduct->owner;
            $model->point_product_id = $data->pointsProduct->id;
            $model->product_type     = $data->pointsProduct->product_type;
            $model->product_id       = $data->pointsProduct->product_id;
            $model->sku_id           = $data->pointsProduct->product_id;
            $model->point            = $data->pointsProduct->point;
            $model->price            = $data->pointsProduct->price;
            $model->quantity         = $data->quantity;
        }

        return $model;
    }
} 