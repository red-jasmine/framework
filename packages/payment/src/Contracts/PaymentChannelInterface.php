<?php

namespace RedJasmine\Payment\Contracts;

use RedJasmine\Payment\Models\Payment;

interface PaymentChannelInterface
{
    /**
     * 创建预支付单
     * @param Payment $payment
     * @return mixed
     */
    public function prePayment(Payment $payment);

}
