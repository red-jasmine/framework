<?php

namespace RedJasmine\PointsMall\Infrastructure\Services\Transformers;

use RedJasmine\Ecommerce\Domain\Data\Order\OrderData;

/**
 * 积分兑换订单创建命令转换器
 */
class PointsExchangeOrderCreateCommandTransformer
{
    /**
     * 转换订单数据为创建命令
     *
     * @param OrderData $orderData
     * @return array
     */
    public function transform(OrderData $orderData): array
    {
        return [
            'order_type' => 'points_exchange',
            'buyer' => $orderData->buyer,
            'seller' => $orderData->seller,
            'products' => $orderData->products,
            'metadata' => $orderData->otherExtra ?? [],
        ];
    }
} 