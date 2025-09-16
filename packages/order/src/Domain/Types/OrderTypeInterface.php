<?php

namespace RedJasmine\Order\Domain\Types;

use RedJasmine\Order\Domain\Models\Order;

interface OrderTypeInterface
{

    public function __construct(array $config = []);

    public static function label() : string;


    public static function type() : string;


    /**
     * 计算订单金额
     *
     * @param  Order  $order
     *
     * @return void
     */
    public function calculateAmount(Order $order) : void;

    /**
     * 订单初始化
     *
     * @param  Order  $order
     *
     * @return void
     */
    public function creating(Order $order) : void;

    /**
     * 订单支付成功
     * - 可能是部分付款需要进行判断 支付状态是 部分付款还是 全部支付
     *
     * @param  Order  $order
     *
     * @return void
     */
    public function paid(Order $order) : void;


    /**
     * 订单接单
     *
     * @param  Order  $order
     *
     * @return void
     */
    public function accept(Order $order) : void;


    /**
     * 订单拒绝
     *
     * @param  Order  $order
     *
     * @return void
     */
    public function reject(Order $order) : void;

    /**
     * 订单发货
     *
     * @param  Order  $order
     *
     * @return void
     */
    public function shipping(Order $order) : void;

    /**
     * 订单发货
     * - 可能是部分发货 需要判断订单状态是 部分发货还是全部发货
     *
     * @param  Order  $order
     *
     * @return void
     */
    public function shipped(Order $order) : void;


    /**
     * 订单确认
     * - 可能是部分确认
     *
     * @param  Order  $order
     *
     * @return void
     */
    public function confirmed(Order $order) : void;

}