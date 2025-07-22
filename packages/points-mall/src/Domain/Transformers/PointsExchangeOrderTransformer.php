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
    public function transform($data, $model): PointsExchangeOrder
    {
        if (!$model instanceof PointsExchangeOrder) {
            $model = new PointsExchangeOrder();
        }

        if ($data instanceof PointsExchangeOrderData) {
            $model->owner_type = $data->owner->getOwnerType();
            $model->owner_id = $data->owner->getOwnerId();
            $model->order_no = $data->orderNo;
            $model->outer_order_no = $data->outerOrderNo;
            $model->point_product_id = $data->pointProductId;
            $model->product_type = $data->productType;
            $model->product_id = $data->productId;
            $model->product_title = $data->productTitle;
            $model->point = $data->point;
            $model->price_currency = $data->priceCurrency;
            $model->price_amount = $data->priceAmount;
            $model->quantity = $data->quantity;
            $model->payment_mode = $data->paymentMode;
            $model->payment_status = $data->paymentStatus;
            $model->status = $data->status;
            $model->exchange_time = $data->exchangeTime;
        }

        return $model;
    }
} 