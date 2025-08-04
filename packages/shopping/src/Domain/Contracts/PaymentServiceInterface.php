<?php

namespace RedJasmine\Shopping\Domain\Contracts;


use RedJasmine\Shopping\Domain\Data\OrderPaymentData;
use RedJasmine\Shopping\Domain\Data\PaymentTradeResult;

/**
 * 支付服务
 * - 发起支付
 * - 获取支付结果
 * - 支付退款
 */
interface PaymentServiceInterface
{

    // 创建支付单
    // 发起支付

    /**
     * 创建支付单
     * @return mixed
     */
    public function create(OrderPaymentData $orderPayment) : PaymentTradeResult;

}