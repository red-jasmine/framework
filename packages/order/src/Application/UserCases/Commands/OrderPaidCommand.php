<?php

namespace RedJasmine\Order\Application\UserCases\Commands;

use DateTime;
use RedJasmine\Support\Data\Data;

class OrderPaidCommand extends Data
{
    /**
     * @param int         $id
     * @param int         $orderPaymentId
     * @param string      $amount
     * @param string      $paymentType 支付源类型
     * @param int         $paymentId 支付ID
     * @param string      $paymentTime 支付时间
     * @param string|null $paymentChannel 支付渠道
     * @param string|null $paymentChannelNo 渠道单号
     * @param string|null $paymentMethod 支付方式
     */
    public function __construct(
        public int     $id,
        public int     $orderPaymentId,
        public string  $amount,
        public string  $paymentType,
        public int     $paymentId,
        public string  $paymentTime,
        public ?string $paymentChannel = null,
        /**
         * 支付渠道单号
         * @var string|null
         */
        public ?string $paymentChannelNo = null,
        public ?string $paymentMethod = null,
    )
    {
    }


}
