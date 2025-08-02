<?php

namespace RedJasmine\Shopping\Domain\Contracts;

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
    public function create();


    public function paying();
}