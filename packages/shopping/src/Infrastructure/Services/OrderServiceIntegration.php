<?php

namespace RedJasmine\Shopping\Infrastructure\Services;

use RedJasmine\Shopping\Domain\Contracts\OrderServiceInterface;
use RedJasmine\Shopping\Domain\Data\OrderData;

/**
 * 订单服务集成
 *
 */
class OrderServiceIntegration implements OrderServiceInterface
{
    /**
     * 创建订单
     *
     * @param  OrderData  $orderData
     *
     * @return mixed
     */
    public function create(OrderData $orderData)
    {
        // TODO: Implement create() method.
    }


}