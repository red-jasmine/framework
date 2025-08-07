<?php

namespace RedJasmine\PointsMall\Domain\Contracts;


use RedJasmine\Ecommerce\Domain\Data\Payment\PaymentTradeData;
use RedJasmine\Ecommerce\Domain\Data\Payment\PaymentTradeResult;


/**
 * 积分商城支付服务接口
 * - 创建支付交易
 * - 获取支付状态
 * - 处理支付回调
 * - 支付退款
 */
interface PaymentServiceInterface
{
    // 创建支付单
    // 发起支付
    /**
     * 业务标识
     */
    public const string BIZ = 'points-mall';

    /**
     * 创建支付单
     *
     * @param  PaymentTradeData  $paymentTradeData
     *
     * @return PaymentTradeResult
     */
    public function create(PaymentTradeData $paymentTradeData) : PaymentTradeResult;

}