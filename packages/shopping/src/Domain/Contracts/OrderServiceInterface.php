<?php

namespace RedJasmine\Shopping\Domain\Contracts;

use RedJasmine\Shopping\Domain\Data\OrderData;
use RedJasmine\Shopping\Domain\Data\OrderProductData;
use RedJasmine\Shopping\Domain\Data\OrdersData;

/**
 * 订单服务接口
 * - 创建订单
 * - 发起支付
 */
interface OrderServiceInterface
{

    public function getOrderProductSplitKey(OrderProductData $orderProductData) : string;

    /**
     * 创建订单
     *
     * @param  OrderData  $orderData
     *
     * @return string 订单号
     * TODO 不能简单返回订单号
     */
    public function create(OrderData $orderData) : string;
}