<?php

namespace RedJasmine\Shopping\Domain\Contracts;

use RedJasmine\Shopping\Domain\Data\OrderData;

/**
 * 订单服务接口
 * - 创建订单
 * - 发起支付
 */
interface OrderServiceInterface
{

    /**
     * 创建订单
     *
     * @param  OrderData  $orderData
     *
     * @return mixed
     */
    public function create(OrderData $orderData);
}