<?php

namespace RedJasmine\Payment\Contracts;

use RedJasmine\Payment\Models\Payment;
use RedJasmine\Support\Contracts\UserInterface;

interface PaymentInterface
{

    /**
     * 查询订单
     * @param int $id
     * @return Payment
     */
    public function find(int $id) :Payment;

    /**
     * 创建预支付DNA
     * @param PaymentTradeInterface $paymentTrade
     * @param UserInterface $owner
     * @return Payment
     * @throws Exception
     */
    public function prePayment(PaymentTradeInterface $paymentTrade, UserInterface $owner) : Payment;

    /**
     * 获取支付方式
     * @param int $id
     * @return Payment
     */
    public function paying(int $id) : Payment;

    /**
     * 支付 选择支付方式
     * @return mixed
     */
    public function payment() : mixed;


    /**
     * 成功通知
     * @return mixed
     */
    public function notify() : mixed;


    public function redirect();
}
